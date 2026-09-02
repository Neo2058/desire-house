<?php

namespace Tests\Feature\Seo;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['setup.enabled' => false]);
    }

    public function test_home_page_renders_title_and_open_graph_tags(): void
    {
        Page::query()->create([
            'title' => 'Главная',
            'slug' => 'home',
            'is_published' => true,
            'blocks' => [],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>', false)
            ->assertSee('Главная', false)
            ->assertSee('og:title', false)
            ->assertSee('og:url', false)
            ->assertSee('schema.org', false)
            ->assertSee('HomeAndConstructionBusiness', false);
    }

    public function test_sitemap_contains_published_pages(): void
    {
        Page::query()->create([
            'title' => 'Главная',
            'slug' => 'home',
            'is_published' => true,
            'blocks' => [],
        ]);

        Page::query()->create([
            'title' => 'Услуги',
            'slug' => 'uslugi',
            'is_published' => true,
            'blocks' => [],
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('urlset', false)
            ->assertSee('/uslugi', false);
    }

    public function test_llms_txt_describes_the_company_and_public_sections(): void
    {
        $this->get('/llms.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=utf-8')
            ->assertSee('Услуги', false)
            ->assertSee('/llms-full.txt', false)
            ->assertSee('/sitemap.xml', false);
    }
}
