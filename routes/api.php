<?php

use App\Http\Controllers\Api\Nasabah\AuthController;
use App\Http\Controllers\Api\Nasabah\AuthorizationController;
use App\Http\Controllers\Api\Nasabah\DashboardController;
use App\Http\Controllers\Api\Nasabah\ForgotPasswordController;
use App\Http\Controllers\Api\Nasabah\InfoController;
use App\Http\Controllers\Api\Nasabah\ProfileController;
use App\Http\Controllers\Api\Nasabah\QrController;
use App\Http\Controllers\Api\Nasabah\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile API Routes for Nasabah (Siswa) — Bank Mini SMKN 1 Bantul
|--------------------------------------------------------------------------
| Seluruh endpoint API berikut dirancang khusus untuk mobile application
| nasabah dengan autentikasi berbasis Laravel Sanctum Bearer Token.
*/

Route::prefix('v1/nasabah')->group(function () {

    // ── Public Endpoints ──
    Route::post('/login', [AuthController::class, 'login'])->name('api.nasabah.login');
    Route::get('/bank-info', [InfoController::class, 'index'])->name('api.nasabah.info');

    // Forgot Password & OTP Flow
    Route::post('/forgot-password/request-otp', [ForgotPasswordController::class, 'requestOtp'])->name('api.nasabah.forgot-password.request-otp');
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('api.nasabah.forgot-password.verify-otp');
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('api.nasabah.forgot-password.reset');

    // ── Authenticated Endpoints (Sanctum) ──
    Route::middleware(['auth:sanctum'])->group(function () {

        // Session & User
        Route::get('/me', [AuthController::class, 'me'])->name('api.nasabah.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.nasabah.logout');

        // Dashboard Ringkasan & Statistik
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('api.nasabah.dashboard');

        // Mutasi & Riwayat Transaksi
        Route::get('/transactions', [TransactionController::class, 'index'])->name('api.nasabah.transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('api.nasabah.transactions.show');

        // Kartu QR Digital untuk Teller
        Route::get('/qr-code', [QrController::class, 'index'])->name('api.nasabah.qr');

        // Profil & Pengaturan Keamanan
        Route::get('/profile', [ProfileController::class, 'show'])->name('api.nasabah.profile.show');
        Route::put('/profile/change-pin', [ProfileController::class, 'changePin'])->name('api.nasabah.profile.change-pin');
        Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('api.nasabah.profile.change-password');
        Route::put('/profile/update-phone', [ProfileController::class, 'updatePhone'])->name('api.nasabah.profile.update-phone');

        // Otorisasi PIN Transaksi dari Teller Mobile
        Route::get('/authorizations/pending', [AuthorizationController::class, 'pending'])->name('api.nasabah.authorizations.pending');
        Route::post('/authorizations/{id}/approve', [AuthorizationController::class, 'approve'])->name('api.nasabah.authorizations.approve');
        Route::post('/authorizations/{id}/reject', [AuthorizationController::class, 'reject'])->name('api.nasabah.authorizations.reject');
    });
});
