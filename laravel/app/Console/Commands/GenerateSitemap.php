<?php

namespace App\Console\Commands;

use App\Platform\Seo\SitemapBuilder;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the public sitemap.xml file';

    public function handle(): int
    {
        $path = public_path('sitemap.xml');

        SitemapBuilder::make()->writeToFile($path);

        $this->info('Sitemap written to '.$path);

        return self::SUCCESS;
    }
}
