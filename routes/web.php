<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RankingVoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentVoteController;
use App\Http\Controllers\FavoriteRankingController;
use App\Http\Controllers\Admin\ModerationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home
Route::get('/', [RankingController::class, 'home'])->name('home');

// Informació
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/settings', function () {
    return inertia('Settings/Configuration');
})->middleware(['auth'])->name('settings.configuration');

// Rankings
Route::middleware('auth')->group(function () {
    Route::get('/rankings/create', [RankingController::class, 'create'])->name('rankings.create');
    Route::post('/rankings', [RankingController::class, 'store'])->name('rankings.store');
});

// Rutes dels rankings (index, store, show, destroy)
Route::resource('rankings', RankingController::class)->only([
    'index', 'store', 'show', 'destroy'
]);

// Votació (només autenticats)
Route::middleware('auth')->group(function () {
    Route::post('/rankings/{ranking}/vote', [RankingVoteController::class, 'vote'])->name('rankings.vote');
    Route::post('/rankings/{ranking}/unvote', [RankingController::class, 'unvote'])->name('rankings.unvote');
});

// Guardem i esborrem comentaris (nested under rankings)
Route::post('/rankings/{ranking}/comments', [CommentController::class, 'store'])->middleware('auth')->name('rankings.comments.store');
Route::delete('/rankings/{ranking}/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('rankings.comments.destroy');

// Votació comentaris (només autenticats)
Route::post('/comments/{comment}/vote', [CommentVoteController::class, 'store'])->middleware('auth')->name('comments.vote');
Route::post('/comments/{comment}/unvote', [CommentController::class, 'unvote'])->name('comments.unvote');

// Rànquings de l'usuari i favorits
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rankings/yours', [RankingController::class, 'yours'])->name('rankings.yours');
    Route::get('/rankings/favorites', [RankingController::class, 'favorites'])->name('rankings.favorites');
    Route::post('/rankings/{ranking}/favorite', [RankingController::class, 'toggleFavorite'])->name('rankings.toggleFavorite');
});

// Favorits
Route::middleware('auth')->group(function () {
    Route::post('/rankings/{ranking}/favorite', [FavoriteRankingController::class, 'toggle']);
    Route::get('/rankings/{ranking}/favorite', [FavoriteRankingController::class, 'check']);
});

// Usuari
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');
});

// Moderació (només per a usuaris amb rol d'admin o moderator)
Route::middleware(['auth', 'can:moderate'])->prefix('admin')->group(function () {
    Route::get('/moderation', [ModerationController::class, 'dashboard'])->name('admin.moderation.dashboard');
    Route::get('/moderation/{ranking}', [ModerationController::class, 'show'])->name('admin.moderation.show');
    Route::post('/moderation/{ranking}/approve', [ModerationController::class, 'approveRanking'])->name('admin.moderation.approve');
    Route::post('/moderation/{ranking}/reject', [ModerationController::class, 'rejectRanking'])->name('admin.moderation.reject');
    Route::post('/moderation/images/{image}/approve', [ModerationController::class, 'approveImage'])->name('admin.moderation.image.approve');
    Route::post('/moderation/images/{image}/reject', [ModerationController::class, 'rejectImage'])->name('admin.moderation.image.reject');
});

require __DIR__.'/auth.php';
