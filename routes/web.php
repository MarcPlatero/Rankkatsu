<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RankingVoteController;
use App\Http\Controllers\CommentController;
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

// Rutes de tots els rankings (index, show, edit, update, create, destroy, store)
Route::resource('rankings', RankingController::class)->only([
    'index', 'store', 'show', 'destroy'
]);

// Votació (només autenticats)
Route::middleware('auth')->group(function () {
    Route::post('/rankings/{ranking}/vote', [RankingVoteController::class, 'vote'])->name('rankings.vote');
    Route::delete('/rankings/{ranking}/unvote', [RankingVoteController::class, 'unvote'])->name('rankings.unvote');
});

// Guardem i esborrem comentaris (nested under rankings)
Route::post('/rankings/{ranking}/comments', [CommentController::class, 'store'])->middleware('auth')->name('rankings.comments.store');
Route::delete('/rankings/{ranking}/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('rankings.comments.destroy');

// Usuari
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
