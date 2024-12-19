<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use Laravel\Passport\Http\Controllers\AuthorizationController;

Route::get('/', function () {
  return ['Laravel' => app()->version()];
});

// Route::get('/sanctum/csrf-cookie', function () {
//   return response()->json(['message' => 'CSRF cookie set']);
// });

Route::middleware(['web'])->group(function () {
  Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle']);
  Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});

Route::middleware(['passport.stateless'])->group(function () {
   Route::post('/oauth/token', [AuthorizationController::class, 'token']);
   Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
});


