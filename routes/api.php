<?php

use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'user']);

    Route::prefix('pages')->controller(PageController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'edit');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');

    });
    Route::get('/parent/pages', [PageController::class, 'parentPages']);

    Route::prefix('sliders')->controller(SliderController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'edit');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');

    });
});

Route::get('/home', [FrontendPageController::class, 'getHomePageSections']);
Route::get('/menu/pages', [FrontendPageController::class, 'getMenuPages']);
Route::get('/parent/{id}/childs', [FrontendPageController::class, 'getChildPages']);
Route::get('/{slug}', [FrontendPageController::class, 'showPage'])->where('slug', '.*');