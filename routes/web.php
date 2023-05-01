<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/', 
    [FilmController::class, 'index']
)->name('films');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [FilmController::class, 'index'])->name('dashboard');

    Route::name('bookings.')->prefix('bookings')->group(function () {
        Route::get(
            '/upcoming', 
            [BookingController::class, 'upcoming']
        )->name('upcoming');

        Route::post(
            '/create', 
            [BookingController::class, 'store']
        )->name('saveBooking');

        Route::get(
            '/past', 
            [FilmController::class, 'index']
        )->name('past');

        Route::get(
            '/cancelled', 
            [FilmController::class, 'index']
        )->name('cancelled');

        Route::get(
            '/create/{filmId}', 
            [BookingController::class, 'create']
        )->name('create');

        Route::get(
            '/{booking}/cancel', 
            [BookingController::class, 'destroy']
        )->name('cancel');
    });
});
