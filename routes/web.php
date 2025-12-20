<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RankingVoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentVoteController;
use App\Http\Controllers\FavoriteRankingController;
use App\Http\Controllers\RankingLikeController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home
Route::get('/', [RankingController::class, 'home'])->name('home');

// Informació
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

// Configuració
Route::get('/settings', function () {
    return inertia('Settings/Configuration');
})->name('settings.configuration');

// Rankings
Route::middleware('auth')->group(function () {
    Route::get('/rankings/create', [RankingController::class, 'create'])->name('rankings.create');
    Route::post('/rankings', [RankingController::class, 'store'])->name('rankings.store');
});

// Rutes dels rankings (index, store, show, destroy)
Route::resource('rankings', RankingController::class)->only([
    'index', 'store', 'show', 'destroy'
]);

// Votació Opcions (només autenticats)
Route::middleware('auth')->group(function () {
    Route::post('/rankings/{ranking}/vote', [RankingVoteController::class, 'vote'])->name('rankings.vote');
    Route::post('/rankings/{ranking}/unvote', [RankingController::class, 'unvote'])->name('rankings.unvote');
});

// Comentaris
Route::post('/rankings/{ranking}/comments', [CommentController::class, 'store'])->middleware('auth')->name('rankings.comments.store');
Route::delete('/rankings/{ranking}/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('rankings.comments.destroy');

// Votació Comentaris
Route::post('/comments/{comment}/vote', [CommentVoteController::class, 'store'])->middleware('auth')->name('comments.vote');
Route::post('/comments/{comment}/unvote', [CommentController::class, 'unvote'])->name('comments.unvote');

// Rànquings de l'usuari i favorits
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rankings/yours', [RankingController::class, 'yours'])->name('rankings.yours');
    Route::get('/rankings/favorites', [RankingController::class, 'favorites'])->name('rankings.favorites');
    Route::post('/rankings/{ranking}/favorite', [FavoriteRankingController::class, 'toggle'])->name('rankings.toggleFavorite');
});

// Likes
Route::middleware('auth')->group(function () {
    Route::post('/rankings/{ranking}/like', [RankingLikeController::class, 'store'])->name('rankings.like');
    Route::post('/rankings/{ranking}/unlike', [RankingLikeController::class, 'destroy'])->name('rankings.unlike');
});

// Usuari (Perfil)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');
});

// Moderació (només admins i moderadors)
Route::middleware(['auth', 'can:moderate'])->prefix('admin')->group(function () {
    Route::get('/moderation', [ModerationController::class, 'dashboard'])->name('admin.moderation.dashboard');
    Route::get('/moderation/{ranking}', [ModerationController::class, 'show'])->name('admin.moderation.show');
    Route::post('/moderation/{ranking}/approve', [ModerationController::class, 'approveRanking'])->name('admin.moderation.approve');
    Route::post('/moderation/{ranking}/reject', [ModerationController::class, 'rejectRanking'])->name('admin.moderation.reject');
    Route::post('/moderation/images/{image}/approve', [ModerationController::class, 'approveImage'])->name('admin.moderation.image.approve');
    Route::post('/moderation/images/{image}/reject', [ModerationController::class, 'rejectImage'])->name('admin.moderation.image.reject');
});

// Rutes de subscripció Premium
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/premium', [SubscriptionController::class, 'index'])->name('premium.index');
    Route::post('/premium/subscribe', [SubscriptionController::class, 'create'])->name('premium.subscribe');
    Route::get('/premium/success', [SubscriptionController::class, 'success'])->name('premium.success');
    Route::get('/premium/manage', [SubscriptionController::class, 'portal'])->name('premium.manage');
});

require __DIR__.'/auth.php';