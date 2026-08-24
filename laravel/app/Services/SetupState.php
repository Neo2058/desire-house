<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class SetupState
{
    public function migrationsAreReady(): bool
    {
        return Schema::hasTable('users')
            && Schema::hasTable('roles')
            && Schema::hasTable('model_has_roles');
    }

    public function hasSuperAdmin(): bool
    {
        return $this->migrationsAreReady()
            && User::query()
                ->whereHas('roles', fn ($query) => $query
                    ->where('name', 'super_admin')
                    ->where('guard_name', 'web'))
                ->exists();
    }

    public function isAvailable(): bool
    {
        return config('setup.enabled')
            && config('setup.token') !== ''
            && $this->migrationsAreReady()
            && ! $this->hasSuperAdmin();
    }
}
