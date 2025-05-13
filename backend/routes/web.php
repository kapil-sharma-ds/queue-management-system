<?php

use App\Http\Controllers\CounterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffSearchController;
use App\Jobs\TestJob;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/test-job', function () {
//     dispatch(new TestJob);
//     return 'Dispatched';
// });

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('data-tables', function () {
        return inertia('DataTables/Index');
    })->name('datatables');
    Route::resource('services-offered', ServiceController::class);
    Route::get('counters', [CounterController::class, 'index'])->name('counter.index');

    Route::prefix('staff')->group(function () {
        Route::get('search', [StaffSearchController::class, 'index'])->name('staff.index');
        // Route::post('search', [StaffSearchController::class, 'search'])->name('staff.search.submit');
        Route::get('/view/{id}', [StaffSearchController::class, 'show'])->name('staff.show');
        Route::get('/{id}/edit', [StaffSearchController::class, 'edit'])->name('staff.edit');
        Route::put('/{id}', [StaffSearchController::class, 'update'])->name('staff.update');
        Route::delete('/{id}', [StaffSearchController::class, 'destroy'])->name('staff.destroy');

        Route::post('clear-cache', [StaffSearchController::class, 'clearStaffCache'])->name('staff.clearStaffCache');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
