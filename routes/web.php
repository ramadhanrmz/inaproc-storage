<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InaprocAccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('inaproc-accounts', InaprocAccountController::class);
Route::get('inaproc-export-pdf', [InaprocAccountController::class, 'exportPdf'])->name('inaproc.export-pdf');