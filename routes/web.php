<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SettlementController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // --- Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Colocations (Explicitly defined) ---
    Route::get('/colocations', [ColocationController::class, 'index'])->name('colocations.index');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/historique', [ColocationController::class, 'historique'])->name('colocations.historique');
    Route::get('/colocations/depense', [ColocationController::class, 'depense'])->name('colocations.depense');

    // Member management routes
    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::put('/colocations/{colocation}', [ColocationController::class, 'update'])->name('colocations.update');
    Route::delete('/colocations/{colocation}', [ColocationController::class, 'destroy'])->name('colocations.destroy');
    Route::post('/colocations/{id}/transfer/{userId}', [ColocationController::class, 'transferOwnership'])->name('colocations.transfer');
    Route::delete('/colocations/{id}/kick/{userId}', [ColocationController::class, 'kick'])->name('colocations.kick');
    Route::delete('/colocations/{id}/quit', [ColocationController::class, 'quit'])->name('colocations.quit');
    Route::post('/colocations/{id}/quit', [ColocationController::class, 'quit'])->name('colocations.quit');
    Route::post('/colocations/{id}/transfer/{userId}', [ColocationController::class, 'transferOwnership'])->name('colocations.transfer');

    // --- Invitations ---
    // Route to process the form on your "show" page
    Route::post('/colocations/{colocation}/invite', [InvitationController::class, 'send'])->name('invitations.send');
    // routes/web.php

    Route::get('/colocations/{colocation}/expenses/create', [ExpenseController::class, 'create'])
        ->name('expenses.create');

    Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])
        ->name('expenses.store');
    Route::get('/colocations/{colocation}/expenses', [ExpenseController::class, 'index'])
        ->name('expenses.index');
    Route::get('/colocations/{colocation}/expenses/{expense}/edit', [ExpenseController::class, 'edit'])
        ->name('expenses.edit');

    Route::put('/colocations/{colocation}/expenses/{expense}', [ExpenseController::class, 'update'])
        ->name('expenses.update');
    Route::patch('/settlements/{settlement}', [SettlementController::class, 'update'])->name('settlements.update');
    // Route for when the roommate clicks the link in their email
    Route::get('/invites/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
    // Add this to your routes/web.php
    Route::post('/invites/refuse/{token}', [InvitationController::class, 'refuse'])->name('invitations.refuse');
    Route::post('/invites/process/{token}', [InvitationController::class, 'process'])->name('invitations.process');
});

require __DIR__ . '/auth.php';
