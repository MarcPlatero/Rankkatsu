<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RankingVoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home
Route::get('/', [RankingController::class, 'home'])->name('home');

// Informació
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

// Rankings
Route::middleware('auth')->group(function () {
    Route::get('/rankings/create', [RankingController::class, 'create'])->name('rankings.create');
    Route::post('/rankings', [RankingController::class, 'store'])->name('rankings.store');
});

// Públic: només veure rànquings
Route::resource('rankings', RankingController::class)->only(['index', 'show']);

// Votació (només autenticats)
Route::middleware('auth')->group(function () {
    Route::post('/rankings/{ranking}/vote', [RankingVoteController::class, 'vote'])->name('rankings.vote');
    Route::delete('/rankings/{ranking}/unvote', [RankingVoteController::class, 'unvote'])->name('rankings.unvote');
});

// Usuari
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
