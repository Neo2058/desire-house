<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InitialPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    private User $administrator;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::query()->create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $this->administrator = User::factory()->create([
            'password' => 'Temporary1!Password',
            'must_change_password' => true,
        ]);
        $this->administrator->assignRole($role);
    }

    public function test_guest_is_sent_to_the_filament_login_page(): void
    {
        $this->get('/first-login/password')
            ->assertRedirect('/admin/login');
    }

    public function test_administrator_with_a_temporary_password_can_open_the_form(): void
    {
        $this->actingAs($this->administrator)
            ->get('/first-login/password')
            ->assertOk()
            ->assertSee('Замените временный пароль');
    }

    public function test_filament_redirects_to_the_password_form_until_password_is_changed(): void
    {
        $this->actingAs($this->administrator)
            ->get('/admin')
            ->assertRedirect('/first-login/password');
    }

    public function test_current_password_must_be_valid(): void
    {
        $this->actingAs($this->administrator)
            ->post('/first-login/password', [
                'current_password' => 'WrongPassword1!',
                'password' => 'NewSecure2!Password',
                'password_confirmation' => 'NewSecure2!Password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue($this->administrator->fresh()->must_change_password);
    }

    public function test_new_password_must_satisfy_the_security_policy(): void
    {
        $this->actingAs($this->administrator)
            ->post('/first-login/password', [
                'current_password' => 'Temporary1!Password',
                'password' => 'weak',
                'password_confirmation' => 'weak',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_administrator_can_replace_the_temporary_password(): void
    {
        $this->actingAs($this->administrator)
            ->post('/first-login/password', [
                'current_password' => 'Temporary1!Password',
                'password' => 'NewSecure2!Password',
                'password_confirmation' => 'NewSecure2!Password',
            ])
            ->assertRedirect('/admin');

        $administrator = $this->administrator->fresh();

        $this->assertFalse($administrator->must_change_password);
        $this->assertTrue(Hash::check('NewSecure2!Password', $administrator->password));
    }

    public function test_completed_user_cannot_reopen_the_first_login_form(): void
    {
        $this->administrator->update(['must_change_password' => false]);

        $this->actingAs($this->administrator)
            ->get('/first-login/password')
            ->assertRedirect('/admin');
    }
}
