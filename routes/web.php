<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Colocations — custom routes BEFORE resource to avoid conflict
    Route::get('/colocations/historique', [ColocationController::class, 'historique'])->name('colocations.historique');
    Route::get('/colocations/depense', [ColocationController::class, 'depense'])->name('colocations.depense');
    Route::post('/colocations/{id}/quit', [ColocationController::class, 'quit'])->name('colocations.quit');
    Route::post('/colocations/{id}/transfer/{userId}', [ColocationController::class, 'transferOwnership'])->name('colocations.transfer');
    
    Route::resource('colocations', ColocationController::class)->except(['edit', 'create']);
});

require __DIR__.'/auth.php';