<?php

declare(strict_types=1);

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityUpdateController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Applications Support Activity Tracker
|--------------------------------------------------------------------------
|
| Maps the HTTP surface of the platform:
|   - Guest authentication (Requirement #6)
|   - Activity intake & daily handover view (Requirements #1, #4)
|   - Personnel status updates with remarks (Requirements #2, #3)
|   - Historical reporting over custom durations (Requirement #5)
|
*/

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard — landing page after login (daily handover view)
    Route::get('/', fn() => redirect()->route('activities.index'))->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Activities — intake & daily overview
    |----------------------------------------------------------------------
    */
    Route::prefix('activities')->name('activities.')->group(function (): void {
        Route::get('/',          [ActivityController::class, 'index'])->name('index');
        Route::get('/create',    [ActivityController::class, 'create'])->name('create');
        Route::post('/',         [ActivityController::class, 'store'])->name('store');

        // Reporting view — query histories across custom durations
        Route::get('/report',    [ActivityController::class, 'report'])->name('report');

        /*
        |------------------------------------------------------------------
        | Status Updates — nested under an activity
        |------------------------------------------------------------------
        */
        Route::post('/{activity}/updates', [ActivityUpdateController::class, 'store'])
            ->whereNumber('activity')
            ->name('updates.store');
    });
});
