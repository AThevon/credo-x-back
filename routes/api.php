<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

use App\Models\Transaction;



// GUEST ROUTES (Accessible without authentication)
Route::middleware(['guest'])->group(function () {
   // Registration and Login
   Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

   // Password Reset
   Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
   Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});


// AUTHENTICATED USER ROUTES (Require auth:sanctum middleware)
Route::middleware(['auth:sanctum'])->group(function () {
   // User Account Management
   Route::get('/user', [RegisteredUserController::class, 'show'])->name('user.show');
   Route::put('/user', [RegisteredUserController::class, 'update'])->name('user.update');
   Route::put('/user/password', [RegisteredUserController::class, 'updatePassword'])->name('user.updatePassword');
   Route::delete('/user', [RegisteredUserController::class, 'destroy'])->name('user.destroy');

   // Email Verification
   Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
      ->middleware(['signed', 'throttle:6,1'])
      ->name('verification.verify');
   Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
      ->middleware('throttle:6,1')
      ->name('verification.send');

   // Categories
   Route::prefix('categories')->group(function () {
      Route::apiResource('/', CategoryController::class);
      Route::get('/{type}', [CategoryController::class, 'getByType'])
         ->where('type', 'income|expense');
   });

   // Transactions
   Route::prefix('transactions')->group(function () {
      Route::apiResource('/', TransactionController::class);
      Route::get('/totals', [TransactionController::class, 'getTotals']);
   });

});