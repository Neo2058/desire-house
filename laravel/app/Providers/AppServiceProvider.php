<?php

namespace App\Providers;

use App\Platform\Seo\SiteSeo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Laravel\SEO\Facades\SEOManager;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('ru');

        $this->configureSeo();
    }

    private function configureSeo(): void
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return;
            }

            $name = SiteSeo::siteName();
            $description = SiteSeo::fallbackDescription();

            config([
                'seo.site_name' => $name,
                'seo.title.suffix' => $name ? ' | '.$name : '',
                'seo.description.fallback' => $description,
                'seo.image.fallback' => SiteSeo::logoUrl(),
            ]);
        } catch (Throwable) {
            return;
        }

        SEOManager::SEODataTransformer(function (SEOData $data): SEOData {
            $data->locale = 'ru';
            $data->site_name ??= SiteSeo::siteName();

            if (! filled($data->description)) {
                $data->description = SiteSeo::fallbackDescription();
            }

            if (! filled($data->image)) {
                $data->image = SiteSeo::logoUrl();
            }

            $data->schema ??= SchemaCollection::initialize();
            $data->schema->add(fn () => SiteSeo::webSite());
            $data->schema->add(fn () => SiteSeo::localBusiness());

            return $data;
        });
    }
}
