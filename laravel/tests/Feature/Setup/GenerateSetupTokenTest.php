<?php

namespace Tests\Feature\Setup;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GenerateSetupTokenTest extends TestCase
{
    use RefreshDatabase;

    private string $environmentPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->environmentPath = tempnam(sys_get_temp_dir(), 'desire-house-env-');

        file_put_contents($this->environmentPath, implode(PHP_EOL, [
            'APP_NAME="Desire House CMS"',
            'INSTALL_ENABLED=false',
            'INSTALL_TOKEN=',
            '',
        ]));

        config(['setup.env_path' => $this->environmentPath]);
    }

    protected function tearDown(): void
    {
        if (isset($this->environmentPath) && is_file($this->environmentPath)) {
            unlink($this->environmentPath);
        }

        parent::tearDown();
    }

    public function test_command_enables_setup_and_writes_a_new_token(): void
    {
        $exitCode = Artisan::call('setup:token');
        $contents = file_get_contents($this->environmentPath);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('INSTALL_ENABLED=true', $contents);
        $this->assertMatchesRegularExpression('/INSTALL_TOKEN=[a-f0-9]{64}/', $contents);
        $this->assertStringContainsString('Install token:', Artisan::output());
    }

    public function test_no_write_mode_does_not_change_the_environment_file(): void
    {
        $before = file_get_contents($this->environmentPath);

        $exitCode = Artisan::call('setup:token', ['--no-write' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame($before, file_get_contents($this->environmentPath));
        $this->assertStringContainsString('environment was not changed', Artisan::output());
    }

    public function test_command_refuses_to_rotate_the_token_after_setup(): void
    {
        $role = Role::query()->create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);
        $user = User::factory()->create();
        $user->assignRole($role);

        $exitCode = Artisan::call('setup:token');

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('super administrator already exists', Artisan::output());
    }
}
