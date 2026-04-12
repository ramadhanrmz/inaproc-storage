<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InaprocAccountController;

/** 
Route::get('/', function () {
    return redirect('/inaproc-accounts');
});

Route::resource('/', InaprocAccountController::class)->names('inaproc-accounts');
#Route::resource('inaproc-accounts', InaprocAccountController::class);
Route::get('inaproc-export-pdf', [InaprocAccountController::class, 'exportPdf'])->name('inaproc.export-pdf');
*/

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