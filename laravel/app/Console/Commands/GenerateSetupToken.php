<?php

namespace App\Console\Commands;

use App\Services\SetupState;
use App\Support\EnvironmentFile;
use Illuminate\Console\Command;
use Throwable;

class GenerateSetupToken extends Command
{
    protected $signature = 'setup:token
        {--force : Generate a new token even when a super administrator exists}
        {--no-write : Print values without changing the local .env file}';

    protected $description = 'Enable the one-time setup master and generate its install token';

    public function handle(SetupState $setupState, EnvironmentFile $environmentFile): int
    {
        if ($setupState->hasSuperAdmin() && ! $this->option('force')) {
            $this->components->error(
                'A super administrator already exists. Use --force only for an intentional recovery operation.'
            );

            return self::FAILURE;
        }

        $token = bin2hex(random_bytes(32));
        $writesEnvironment = ! $this->option('no-write');

        if ($writesEnvironment) {
            try {
                $environmentFile->update(config('setup.env_path'), [
                    'INSTALL_ENABLED' => 'true',
                    'INSTALL_TOKEN' => $token,
                ]);
            } catch (Throwable $exception) {
                $this->components->error($exception->getMessage());

                return self::FAILURE;
            }

            $this->callSilent('config:clear');
        }

        $setupUrl = rtrim(config('app.url'), '/').'/setup';

        $this->newLine();
        $this->components->info('Desire House CMS is ready for initial setup.');
        $this->line("Setup URL: <fg=cyan>{$setupUrl}</>");
        $this->line("Install token: <fg=yellow;options=bold>{$token}</>");
        $this->newLine();

        if ($writesEnvironment) {
            $this->line('INSTALL_ENABLED and INSTALL_TOKEN were written to the local .env file.');
            $this->warn('Restart PHP-FPM, queue workers, Octane, or the development server before opening the setup URL.');
        } else {
            $this->warn('The environment was not changed. Export INSTALL_ENABLED=true and INSTALL_TOKEN before opening the setup URL.');
        }

        $this->warn('The token becomes unusable as soon as a user receives the super_admin role.');

        return self::SUCCESS;
    }
}
