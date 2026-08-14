<?php

namespace App\CMS\Dashboard\Widgets;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WelcomeWidget extends AbstractWidget
{
    public static function key(): string
    {
        return 'welcome';
    }

    public static function title(): string
    {
        return 'Добро пожаловать';
    }

    public function data(): array
    {
        return [

            'user' => Auth::user()?->name,

            'company' => 'Desire House CMS',

            'date' => Carbon::now()->translatedFormat('d F Y'),

        ];
    }
}