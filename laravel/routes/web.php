<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;

Route::get(
    '/uslugi/{service:slug}',
    ServiceController::class
);

Route::get(
    '/raboty/{project:slug}',
    ProjectController::class
);

Route::get(
    '/{slug?}',
    PageController::class
);
