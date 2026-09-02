<?php

namespace App\Platform\Seo;

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

final class SitemapBuilder
{
    public static function make(): Sitemap
    {
        $sitemap = Sitemap::create();

        Page::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->each(function (Page $page) use ($sitemap): void {
                $url = $page->slug === 'home' ? url('/') : url('/'.$page->slug);

                $tag = Url::create($url)
                    ->setLastModificationDate($page->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority($page->slug === 'home' ? 1.0 : 0.8);

                $sitemap->add($tag);
            });

        $sitemap->add(
            Url::create(url('/raboty'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8)
        );

        Service::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->each(function (Service $service) use ($sitemap): void {
                $sitemap->add(
                    Url::create(url('/uslugi/'.$service->slug))
                        ->setLastModificationDate($service->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            });

        Project::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->each(function (Project $project) use ($sitemap): void {
                $sitemap->add(
                    Url::create(url('/raboty/'.$project->slug))
                        ->setLastModificationDate($project->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.6)
                );
            });

        return $sitemap;
    }
}
