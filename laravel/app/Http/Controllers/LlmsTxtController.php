<?php

namespace App\Http\Controllers;

use App\Platform\Geo\LlmsDocument;

class LlmsTxtController extends Controller
{
    public function index()
    {
        return static::plain(LlmsDocument::index());
    }

    public function full()
    {
        return static::plain(LlmsDocument::full());
    }

    private static function plain(string $body)
    {
        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
