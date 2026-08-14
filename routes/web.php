<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Teller;
use App\Http\Controllers\Supervisor;
use App\Http\Controllers\Nasabah;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('role:Administrator')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', Admin\UserController::class)->except(['show']);

    // Nasabah Management
    Route::resource('nasabah', Admin\NasabahController::class);

    // Transactions (read-only)
    Route::get('/transactions', [Admin\TransactionController::class, 'index'])->name('transactions.index');

    // Jurnal Akuntansi (read-only)
    Route::get('/journals', [Admin\JournalController::class, 'index'])->name('journals.index');
});

/*
|--------------------------------------------------------------------------
| Teller Routes
|--------------------------------------------------------------------------
*/

Route::prefix('teller')->name('teller.')->middleware('role:Teller')->group(function () {
    Route::get('/dashboard', [Teller\DashboardController::class, 'index'])->name('dashboard');

    // Nasabah lookup
    Route::get('/nasabah', [Teller\NasabahController::class, 'index'])->name('nasabah.index');
    Route::post('/nasabah/qr-lookup', [Teller\NasabahController::class, 'qrLookup'])->name('nasabah.qr-lookup');

    // Deposit
    Route::get('/deposit', [Teller\DepositController::class, 'create'])->name('deposit.create');
    Route::post('/deposit', [Teller\DepositController::class, 'store'])->name('deposit.store');

    // Withdrawal
    Route::get('/withdrawal', [Teller\WithdrawalController::class, 'create'])->name('withdrawal.create');
    Route::post('/withdrawal', [Teller\WithdrawalController::class, 'store'])->name('withdrawal.store');

    // Transaction history
    Route::get('/transactions', [Teller\TransactionController::class, 'index'])->name('transactions.index');

    // Daily Reports
    Route::get('/reports', [Teller\DailyReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [Teller\DailyReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [Teller\DailyReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [Teller\DailyReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/submit', [Teller\DailyReportController::class, 'submit'])->name('reports.submit');
});

/*
|--------------------------------------------------------------------------
| Supervisor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('supervisor')->name('supervisor.')->middleware('role:Supervisor')->group(function () {
    Route::get('/dashboard', [Supervisor\DashboardController::class, 'index'])->name('dashboard');

    // Report review
    Route::get('/reports', [Supervisor\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [Supervisor\ReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/approve', [Supervisor\ReportController::class, 'approve'])->name('reports.approve');
    Route::patch('/reports/{report}/reject', [Supervisor\ReportController::class, 'reject'])->name('reports.reject');

    // Transactions (read-only)
    Route::get('/transactions', [Supervisor\TransactionController::class, 'index'])->name('transactions.index');

    // Journals (read-only)
    Route::get('/journals', [Supervisor\JournalController::class, 'index'])->name('journals.index');
});

/*
|--------------------------------------------------------------------------
| Nasabah Routes
|--------------------------------------------------------------------------
*/

Route::prefix('nasabah')->name('nasabah.')->middleware('nasabah')->group(function () {
    Route::get('/dashboard', [Nasabah\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mutasi', [Nasabah\MutasiController::class, 'index'])->name('mutasi.index');
});
