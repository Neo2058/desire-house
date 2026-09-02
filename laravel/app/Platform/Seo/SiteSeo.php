<?php

namespace App\Platform\Seo;

use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

final class SiteSeo
{
    public static function settings(): ?SiteSetting
    {
        return SiteSetting::query()->with('logo')->first();
    }

    public static function siteName(): ?string
    {
        return static::settings()?->company_name ?: config('app.name');
    }

    public static function logoUrl(): ?string
    {
        $url = static::settings()?->logo?->url;

        return filled($url) ? $url : null;
    }

    public static function fallbackDescription(): string
    {
        $name = static::siteName() ?: 'Desire House';

        return "Строительная компания {$name}: проектирование и строительство домов, бань и других объектов под ключ.";
    }

    public static function worksIndexData(): SEOData
    {
        $name = static::siteName();

        $schema = SchemaCollection::initialize();
        $works = static::worksItemList();

        if ($works) {
            $schema->add(fn () => $works);
        }

        return new SEOData(
            title: 'Наши работы',
            description: "Реализованные объекты {$name}: дома, бани и другие проекты по направлениям услуг.",
            image: static::logoUrl(),
            url: url('/raboty'),
            locale: 'ru',
            canonical_url: url('/raboty'),
            schema: $schema,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function webSite(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => static::siteName(),
            'url' => url('/'),
            'inLanguage' => 'ru',
            'description' => static::fallbackDescription(),
            'publisher' => [
                '@type' => 'HomeAndConstructionBusiness',
                'name' => static::siteName(),
                'url' => url('/'),
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function servicesItemList(): ?array
    {
        $services = Service::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get();

        if ($services->isEmpty()) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Услуги',
            'itemListElement' => $services->values()->map(fn (Service $service, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $service->title,
                'url' => url('/uslugi/'.$service->slug),
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function worksItemList(): ?array
    {
        $projects = Project::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get();

        if ($projects->isEmpty()) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Наши работы',
            'itemListElement' => $projects->values()->map(fn (Project $project, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $project->title,
                'url' => url('/raboty/'.$project->slug),
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function localBusiness(): array
    {
        $settings = static::settings();
        $name = static::siteName();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'HomeAndConstructionBusiness',
            'name' => $name,
            'url' => url('/'),
            'description' => static::fallbackDescription(),
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'RU',
            ],
        ];

        $offers = Service::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->get()
            ->map(fn (Service $service) => [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => $service->title,
                    'url' => url('/uslugi/'.$service->slug),
                ],
            ])
            ->all();

        if ($offers !== []) {
            $schema['makesOffer'] = $offers;
        }

        if ($settings?->phone) {
            $schema['telephone'] = $settings->phone;
        }

        if ($settings?->email) {
            $schema['email'] = $settings->email;
        }

        if ($settings?->address) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings->address,
                'addressCountry' => 'RU',
            ];
        }

        $sameAs = array_values(array_filter([
            $settings?->telegram,
            $settings?->whatsapp,
        ]));

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        $logo = static::logoUrl();

        if ($logo) {
            $schema['image'] = $logo;
            $schema['logo'] = $logo;
        }

        return $schema;
    }
}
