<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Models\SEO;
use RalphJSmit\Laravel\SEO\Schema\BreadcrumbListSchema;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

trait HasPublicSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(config('seo.model', SEO::class), 'model')->withDefault();
    }

    protected static function bootHasPublicSeo(): void
    {
        static::saved(function (self $model): void {
            $model->seo()->firstOrCreate([]);
        });
    }

    public function getDynamicSEOData(): SEOData
    {
        $stored = $this->seo;

        return new SEOData(
            title: filled($stored?->title) ? $stored->title : $this->seoFallbackTitle(),
            description: filled($stored?->description) ? $stored->description : $this->seoFallbackDescription(),
            image: $this->seoImageUrl(),
            url: $this->seoUrl(),
            locale: 'ru',
            canonical_url: $this->seoUrl(),
            schema: $this->seoSchema(),
        );
    }

    abstract public function seoUrl(): string;

    protected function seoFallbackTitle(): string
    {
        return (string) $this->title;
    }

    protected function seoFallbackDescription(): ?string
    {
        return $this->plainSeoText($this->description ?? null);
    }

    protected function plainSeoText(?string $text): ?string
    {
        if (! filled($text)) {
            return null;
        }

        $plain = trim(preg_replace('/\s+/', ' ', strip_tags((string) $text) ?? '') ?? '');

        return $plain !== '' ? Str::limit($plain, 160) : null;
    }

    protected function seoImageUrl(): ?string
    {
        return null;
    }

    protected function seoSchema(): SchemaCollection
    {
        $schema = SchemaCollection::initialize();
        $crumbs = $this->seoBreadcrumbs();

        if ($crumbs !== []) {
            $schema->addBreadcrumbs(function (BreadcrumbListSchema $breadcrumbs) use ($crumbs) {
                return $breadcrumbs->prependBreadcrumbs($crumbs);
            });
        }

        $entity = $this->seoEntitySchema();

        if ($entity) {
            $schema->add(fn () => $entity);
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function seoEntitySchema(): ?array
    {
        return null;
    }

    /**
     * @return array<string, string>
     */
    protected function seoBreadcrumbs(): array
    {
        return [
            'Главная' => url('/'),
        ];
    }
}
