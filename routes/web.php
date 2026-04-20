<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InaprocAccountController; // Pastikan ini di-import
use Illuminate\Support\Facades\Route;

// 1. REDIRECT HALAMAN DEPAN
// Jika user belum login, Breeze otomatis arahkan ke /login (dari middleware auth)
Route::get('/', function () {
    return redirect()->route('inaproc-accounts.index');
});

// 2. SEMUA ROUTE YANG WAJIB LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (Bawaan Breeze, dialihkan ke accounts index)
    Route::get('/dashboard', function () {
        return redirect()->route('inaproc-accounts.index');
    })->name('dashboard');

    // --- ROUTE UTAMA INAPROC ---
    Route::get('/accounts/grafik', [InaprocAccountController::class, 'grafik'])->name('inaproc.grafik');

    Route::resource('accounts', InaprocAccountController::class)
        ->parameters(['accounts' => 'inaprocAccount'])
        ->names('inaproc-accounts');

    Route::get('/export/pdf', [InaprocAccountController::class, 'exportPdf'])->name('inaproc.export-pdf');
    Route::get('/export/xlsx', [InaprocAccountController::class, 'exportXlsx'])->name('inaproc.export-xlsx');

    // --- ROUTE PROFILE USER (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/inaproc/import', [InaprocAccountController::class, 'import'])->name('inaproc.import');
    Route::get('/inaproc/download-template', [InaprocAccountController::class, 'downloadTemplate'])->name('inaproc.download-template');
});

require __DIR__.'/auth.php';