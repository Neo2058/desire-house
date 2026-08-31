<?php

namespace Tests\Feature\Setup;

use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupMasterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'setup.enabled' => true,
            'setup.token' => 'test-install-token',
        ]);
    }

    public function test_setup_page_is_available_before_the_first_user_exists(): void
    {
        $this->get('/setup')
            ->assertOk()
            ->assertSee('Первоначальная настройка');
    }

    public function test_home_redirects_to_setup_before_a_super_admin_exists(): void
    {
        $this->get('/')->assertRedirect('/setup');
    }

    public function test_a_regular_user_does_not_close_the_setup_master(): void
    {
        User::factory()->create();

        $this->get('/')->assertRedirect('/setup');
        $this->get('/setup')->assertOk();
    }

    public function test_setup_rejects_an_invalid_install_token(): void
    {
        $this->post('/setup', [
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'install_token' => 'wrong-token',
        ])->assertSessionHasErrors('install_token');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_setup_creates_one_super_admin_and_then_closes(): void
    {
        $response = $this->post('/setup', [
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'install_token' => 'test-install-token',
        ]);

        $response
            ->assertOk()
            ->assertSee('Администратор создан')
            ->assertSee('owner@example.com');

        $user = User::query()->sole();

        $this->assertTrue($user->hasRole('super_admin'));
        $this->assertTrue($user->must_change_password);
        $this->assertTrue($user->canAccessPanel(Panel::make()->id('admin')));

        $this->get('/setup')->assertRedirect('/admin');
        $this->post('/setup', [
            'name' => 'Second owner',
            'email' => 'second@example.com',
            'install_token' => 'test-install-token',
        ])->assertRedirect('/admin');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_without_an_admin_role_cannot_access_the_panel(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->canAccessPanel(Panel::make()->id('admin')));
    }

    public function test_setup_is_hidden_when_install_mode_is_disabled(): void
    {
        config(['setup.enabled' => false]);

        $this->get('/setup')->assertNotFound();
    }
}
