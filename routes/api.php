<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/characters', [CharacterController::class, 'index'])->name('characterIndex');

Route::get('/characters/schema', function() {
    return Storage::get('character.json');
})->name('characterSchema');

Route::get('/characters/{id}', [CharacterController::class, 'show'])->name('characterShow');


Route::get('/episodes', [EpisodeController::class, 'index'])->name('episodeIndex');
Route::get('/episodes/{id}', [EpisodeController::class, 'show'])->name('episodeShow');


Route::get('/locations', [LocationController::class, 'index'])->name('locationIndex');
Route::get('/locations/{id}', [LocationController::class, 'show'])->name('locationShow');
