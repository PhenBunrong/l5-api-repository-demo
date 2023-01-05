<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::get('auth/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth:api', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('auth/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:api', 'throttle:6,1'])
    ->name('verification.send');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::prefix('password')->middleware(['guest:api'])->name('password.')->group(function () {
        Route::post('forget', [PasswordResetLinkController::class, 'store'])->name('email');
        Route::post('reset', [NewPasswordController::class, 'store'])->name('update');
    });
    Route::get('me', [AuthController::class, 'index'])->name('me');
    Route::post('login', [AuthController::class, 'store'])->name('login');
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
});