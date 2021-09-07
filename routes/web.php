<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\ListingController::class, 'index'])
    ->name('listings.index');

Route::get('/new', [\App\Http\Controllers\ListingController::class, 'create'])
    ->name('listings.create');

Route::post('/new', [\App\Http\Controllers\ListingController::class, 'store'])
    ->name('listings.store');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'listings' => auth()->user()->listings
    ]);
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/{listing}', [\App\Http\Controllers\ListingController::class, 'show'])
    ->name('listings.show');

Route::get('/{listing}/apply', [\App\Http\Controllers\ListingController::class, 'apply'])
    ->name('listings.apply');
