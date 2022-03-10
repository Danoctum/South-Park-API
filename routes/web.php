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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function() {
    return view('about', [
        'characterCount' => App\Models\Character::count(),
        'episodeCount' => App\Models\Episode::count(),
        'locationCount' => App\Models\Location::count(),
        'familyCount' => \App\Models\Family::count(),
    ]);
})->name('about');

Route::get('/docs', function() {
    return view('docs');
})->name('docs');
