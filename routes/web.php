<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Borrower\Catalog;
use App\Livewire\Borrower\Cart;
use App\Livewire\Borrower\MyLoans;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LogExportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff') {
        return redirect('/admin');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/catalog', Catalog::class)->name('catalog');
    Route::get('/cart', Cart::class)->name('cart');
    Route::get('/my-loans', MyLoans::class)->name('my-loans');

    Route::prefix('report')->group(function () {
        Route::post('/summary', [ReportController::class, 'exportSummary'])->name('report.summary');
        Route::post('/filtered', [ReportController::class, 'exportFiltered'])->name('report.filtered');
        Route::post('/all', [ReportController::class, 'exportAll'])->name('report.all');
    });

    Route::get('/log', [LogExportController::class, 'exportLog'])->name('log');
});

require __DIR__.'/auth.php';
