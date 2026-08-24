<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SetupState;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class SetupController extends Controller
{
    public function show(): View
    {
        return view('setup.index');
    }

    public function store(Request $request, SetupState $setupState): View
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'install_token' => ['required', 'string'],
        ]);

        if (! hash_equals(config('setup.token'), $data['install_token'])) {
            throw ValidationException::withMessages([
                'install_token' => 'Неверный установочный токен.',
            ]);
        }

        $password = Str::password(20);

        $user = DB::transaction(function () use ($data, $password, $setupState): User {
            DB::statement('LOCK TABLE users IN ACCESS EXCLUSIVE MODE');

            abort_if($setupState->hasSuperAdmin(), 409, 'Administrator already exists.');

            $role = Role::query()->firstOrCreate([
                'name' => 'super_admin',
                'guard_name' => 'web',
            ]);

            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $password,
                'must_change_password' => true,
            ]);

            $user->assignRole($role);

            return $user;
        }, 3);

        return view('setup.complete', [
            'login' => $user->email,
            'password' => $password,
        ]);
    }
}
