<?php

namespace App\Models;

use App\Support\HasPublicSeo;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model implements HasMedia
{
    use HasPublicSeo;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'city',
        'area',
        'short_description',
        'description',
        'is_featured',
        'is_published',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function getAreaLabelAttribute(): string
    {
        return "{$this->area} м²";
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function seoUrl(): string
    {
        return url('/raboty/'.$this->slug);
    }

    protected function seoFallbackDescription(): ?string
    {
        $fromDescription = $this->plainSeoText($this->description ?? null);

        if ($fromDescription) {
            return $fromDescription;
        }

        $parts = array_filter([
            $this->city,
            $this->area ? $this->area_label : null,
        ]);

        return $parts ? implode(' · ', $parts) : null;
    }

    protected function seoImageUrl(): ?string
    {
        $cover = $this->getFirstMediaUrl('cover');

        return $cover !== '' ? $cover : null;
    }

    protected function seoBreadcrumbs(): array
    {
        $this->loadMissing('services');
        $parent = $this->services->first();

        $crumbs = [
            'Главная' => url('/'),
            'Наши работы' => url('/raboty'),
        ];

        if ($parent) {
            $crumbs[$parent->title] = url('/uslugi/'.$parent->slug);
        }

        return $crumbs;
    }

    protected function seoEntitySchema(): ?array
    {
        $this->loadMissing('services');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $this->title,
            'url' => $this->seoUrl(),
            'inLanguage' => 'ru',
        ];

        $description = $this->seoFallbackDescription();

        if ($description) {
            $schema['description'] = $description;
        }

        if ($this->city) {
            $schema['contentLocation'] = [
                '@type' => 'Place',
                'name' => $this->city,
            ];
        }

        $image = $this->seoImageUrl();

        if ($image) {
            $schema['image'] = $image;
        }

        if ($this->services->isNotEmpty()) {
            $schema['about'] = $this->services
                ->map(fn (Service $service) => [
                    '@type' => 'Service',
                    'name' => $service->title,
                    'url' => url('/uslugi/'.$service->slug),
                ])
                ->values()
                ->all();
        }

        return $schema;
    }
}
