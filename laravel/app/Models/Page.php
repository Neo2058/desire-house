<?php

namespace App\Models;

use App\Platform\Seo\SiteSeo;
use App\Support\HasPublicSeo;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasPublicSeo;
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'slug',
        'blocks',
        'is_published',
    ];

    protected $casts = [
        'blocks' => 'array',
        'is_published' => 'boolean',
    ];

    public function seoUrl(): string
    {
        return $this->slug === 'home'
            ? url('/')
            : url('/'.$this->slug);
    }

    protected function seoBreadcrumbs(): array
    {
        if ($this->slug === 'home') {
            return [];
        }

        return [
            'Главная' => url('/'),
        ];
    }

    protected function seoEntitySchema(): ?array
    {
        return $this->slug === 'uslugi'
            ? SiteSeo::servicesItemList()
            : null;
    }
}
