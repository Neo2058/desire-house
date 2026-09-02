<?php

namespace App\Models;

use App\Platform\Seo\SiteSeo;
use App\Support\HasPublicSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasPublicSeo;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'is_published',
        'is_featured',
        'sort_order',
        'blocks',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    protected function casts(): array
    {
        return [
            'blocks' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function seoUrl(): string
    {
        return url('/uslugi/'.$this->slug);
    }

    protected function seoImageUrl(): ?string
    {
        $cover = $this->getFirstMediaUrl('cover');

        return $cover !== '' ? $cover : null;
    }

    protected function seoBreadcrumbs(): array
    {
        return [
            'Главная' => url('/'),
            'Услуги' => url('/uslugi'),
        ];
    }

    protected function seoEntitySchema(): ?array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $this->title,
            'url' => $this->seoUrl(),
            'provider' => [
                '@type' => 'HomeAndConstructionBusiness',
                'name' => SiteSeo::siteName(),
                'url' => url('/'),
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'RU',
            ],
        ];

        $description = $this->seoFallbackDescription();

        if ($description) {
            $schema['description'] = $description;
        }

        $image = $this->seoImageUrl();

        if ($image) {
            $schema['image'] = $image;
        }

        return $schema;
    }
}
