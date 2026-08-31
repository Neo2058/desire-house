<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectIndexController;


Route::post(
    '/lead',
    [LeadController::class, 'store']
)->name('lead.store');


Route::get(
    '/uslugi/{service:slug}',
    ServiceController::class
);


Route::get(
    '/raboty',
    ProjectIndexController::class
);

Route::get(
    '/raboty/{project:slug}',
    ProjectController::class
);


Route::get(
    '/{slug?}',
    PageController::class
);
