<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class InitialPasswordController extends Controller
{
    public function edit(Request $request): View|RedirectResponse
    {
        if (! $request->user()->must_change_password) {
            return redirect('/admin');
        }

        return view('auth.initial-password');
    }

    public function update(Request $request): RedirectResponse
    {
        if (! $request->user()->must_change_password) {
            return redirect('/admin');
        }

        $data = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $request->user()->forceFill([
            'password' => $data['password'],
            'must_change_password' => false,
        ])->save();

        $request->session()->regenerate();

        return redirect('/admin')->with(
            'status',
            'Пароль изменён. Добро пожаловать в Desire House CMS.',
        );
    }
}
