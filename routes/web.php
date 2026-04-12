<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InaprocAccountController;

/** 
Route::prefix('inaproc-accounts')->group(function () {
    
    // Halaman Utama (Daftar Akun)
    Route::get('/', [InaprocAccountController::class, 'index'])->name('inaproc-accounts.index');
    
    // Resource Route untuk Create, Edit, Delete, dll
    Route::resource('accounts', InaprocAccountController::class)->names([
        'index'   => 'inaproc-accounts.index',
        'create'  => 'inaproc-accounts.create',
        'store'   => 'inaproc-accounts.store',
        'show'    => 'inaproc-accounts.show',
        'edit'    => 'inaproc-accounts.edit',
        'update'  => 'inaproc-accounts.update',
        'destroy' => 'inaproc-accounts.destroy',
    ]);

    // Export Routes
    Route::get('/export/pdf', [InaprocAccountController::class, 'exportPdf'])->name('inaproc.export-pdf');
});
**/

// Halaman Utama (Sekarang langsung di root /)
Route::get('/', [InaprocAccountController::class, 'index'])->name('inaproc-accounts.index');

Route::resource('accounts', InaprocAccountController::class)
    ->parameters(['accounts' => 'inaprocAccount'])
    ->names('inaproc-accounts');

// Export Routes
Route::get('/export/pdf', [InaprocAccountController::class, 'exportPdf'])->name('inaproc.export-pdf');