<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\InitialPasswordController;

Route::middleware(['auth', 'throttle:10,1'])
    ->controller(InitialPasswordController::class)
    ->group(function (): void {
        Route::get('/first-login/password', 'edit')->name('password.first.edit');
        Route::post('/first-login/password', 'update')->name('password.first.update');
    });

Route::middleware(['setup.available', 'throttle:5,1'])
    ->controller(SetupController::class)
    ->group(function (): void {
        Route::get('/setup', 'show')->name('setup.show');
        Route::post('/setup', 'store')->name('setup.store');
    });


Route::post(
    '/lead',
    [LeadController::class, 'store']
)->name('lead.store');


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
