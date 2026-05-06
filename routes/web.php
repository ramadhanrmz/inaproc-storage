<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InaprocAccountController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 1. REDIRECT HALAMAN DEPAN
Route::get('/', function () {
    return redirect()->route('inaproc-accounts.index');
});

// 2. SEMUA ROUTE YANG WAJIB LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (Bawaan Breeze, dialihkan ke accounts index)
    Route::get('/dashboard', function () {
        return redirect()->route('inaproc-accounts.index');
    })->name('dashboard');

    // --- ROUTE UTAMA INAPROC (Semua user bisa lihat index & grafik) ---
    Route::get('/accounts/grafik', [InaprocAccountController::class, 'grafik'])->name('inaproc.grafik');
    Route::get('/accounts', [InaprocAccountController::class, 'index'])->name('inaproc-accounts.index');
    Route::get('/accounts/{inaprocAccount}', [InaprocAccountController::class, 'show'])->name('inaproc-accounts.show');
    Route::get('/export/pdf-detail', [InaprocAccountController::class, 'exportPdfDetail'])->name('inaproc.export-pdf-detail');

    // --- ROUTE ADMIN ONLY ---
    Route::middleware(['can:admin-access'])->group(function () {
        // CRUD Akun Inaproc (Store, Edit, Update, Destroy)
        Route::post('/accounts', [InaprocAccountController::class, 'store'])->name('inaproc-accounts.store');
        Route::get('/accounts/{inaprocAccount}/edit', [InaprocAccountController::class, 'edit'])->name('inaproc-accounts.edit');
        Route::put('/accounts/{inaprocAccount}', [InaprocAccountController::class, 'update'])->name('inaproc-accounts.update');
        Route::delete('/accounts/{inaprocAccount}', [InaprocAccountController::class, 'destroy'])->name('inaproc-accounts.destroy');

        // OPD Management
        Route::post('/opds/bulk-delete', [OpdController::class, 'bulkDelete'])->name('opds.bulk-delete');
        Route::resource('opds', OpdController::class);

        // User Management
        Route::resource('users', UserController::class);

        // Export & Import
        Route::get('/export/pdf', [InaprocAccountController::class, 'exportPdf'])->name('inaproc.export-pdf');
        Route::get('/export/xlsx', [InaprocAccountController::class, 'exportXlsx'])->name('inaproc.export-xlsx');
        Route::post('/inaproc/import', [InaprocAccountController::class, 'import'])->name('inaproc.import');
        Route::get('/inaproc/download-template', [InaprocAccountController::class, 'downloadTemplate'])->name('inaproc.download-template');
        Route::post('/inaproc/bulk-delete', [InaprocAccountController::class, 'bulkDelete'])->name('inaproc.bulk-delete');
    });

    // --- ROUTE PROFILE USER (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';