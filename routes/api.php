<?php

use App\Http\Controllers\Api\FallbackController;
use App\Http\Controllers\Api\MatchController;
use Illuminate\Support\Facades\Route;

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

/**
 * ===================================================================
 * MATCH ROUTES
 * ===================================================================
 */
Route::get('match/{property}', [MatchController::class, 'getMatchingSearchProfiles'])->name('api.property.search-profiles');

Route::fallback([FallbackController::class, 'missing']);
