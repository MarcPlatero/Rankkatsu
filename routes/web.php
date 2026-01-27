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
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

// Pàgina principal
Route::get('/', [RankingController::class, 'home'])->name('home');

// Canvi d'idioma de l'aplicació
Route::get('/language/{locale}', function ($locale) {
    // Validem que l'idioma sigui un dels permesos
    if (in_array($locale, ['ca', 'es', 'en', 'fr', 'de', 'it', 'pt', 'gl', 'eu'])) {
        Session::put('locale', $locale);
    }
    // Retornem l'usuari a la pàgina on estava
    return back();
})->name('language.switch');

// Pàgines informatives i de configuració bàsica
Route::get('/about', function () { return Inertia::render('About'); })->name('about');
Route::get('/settings', function () { return inertia('Settings/Configuration'); })->name('settings.configuration');

// Pàgines legals (Termes, Privacitat, Cookies)
Route::get('/legal/terms', function () { return Inertia::render('Legal/Terms'); })->name('legal.terms');
Route::get('/legal/privacy', function () { return Inertia::render('Legal/Privacy'); })->name('legal.privacy');
Route::get('/legal/cookies', function () { return Inertia::render('Legal/Cookies'); })->name('legal.cookies');

// Índex públic de tots els rànquings
Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');

// Grup de rutes per a usuaris autenticats (Crear, Guardar, Esborrar, etc.)
Route::middleware('auth')->group(function () {
    Route::get('/rankings/create', [RankingController::class, 'create'])->name('rankings.create');
    Route::post('/rankings', [RankingController::class, 'store'])->name('rankings.store');
    
    Route::delete('/rankings/{ranking}', [RankingController::class, 'destroy'])->name('rankings.destroy');
    Route::post('/rankings/{ranking}/like', [RankingLikeController::class, 'store'])->name('rankings.like');
    Route::post('/rankings/{ranking}/unlike', [RankingLikeController::class, 'destroy'])->name('rankings.unlike');
    
    Route::delete('/rankings/{ranking}/comments/{comment}', [CommentController::class, 'destroy'])->name('rankings.comments.destroy');
    Route::post('/rankings/{ranking}/unvote', [RankingController::class, 'unvote'])->name('rankings.unvote');
    Route::post('/comments/{comment}/unvote', [CommentController::class, 'unvote'])->name('comments.unvote');
});

// Llistes personals (Els meus rànquings i favorits) - Requereix email verificat
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rankings/yours', [RankingController::class, 'yours'])->name('rankings.yours');
    Route::get('/rankings/favorites', [RankingController::class, 'favorites'])->name('rankings.favorites');
    Route::post('/rankings/{ranking}/favorite', [FavoriteRankingController::class, 'toggle'])->name('rankings.toggleFavorite');
});

// Accions de votació i comentaris
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::post('/rankings/{ranking}/vote', [RankingVoteController::class, 'vote'])->name('rankings.vote');
    Route::post('/rankings/{ranking}/comments', [CommentController::class, 'store'])->name('rankings.comments.store');
    Route::post('/comments/{comment}/vote', [CommentVoteController::class, 'store'])->name('comments.vote');
});

// Visualització d'un rànquing específic
Route::get('/rankings/{ranking}', [RankingController::class, 'show'])->name('rankings.show');

// Gestió de Subscripcions Premium (Stripe)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/premium', [SubscriptionController::class, 'index'])->name('premium.index');
    Route::post('/premium/subscribe', [SubscriptionController::class, 'create'])->name('premium.subscribe');
    Route::get('/premium/success', [SubscriptionController::class, 'success'])->name('premium.success');
    Route::get('/premium/manage', [SubscriptionController::class, 'portal'])->name('premium.manage');
});

// Gestió del Perfil d'Usuari i Notificacions
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');

    // Marcar notificacions com a llegides
    Route::post('/notifications/mark-read', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markRead');
});

// Rutes d'administració i moderació
Route::middleware(['auth', 'can:moderate'])->prefix('admin')->group(function () {
    Route::get('/moderation', [ModerationController::class, 'dashboard'])->name('admin.moderation.dashboard');
    Route::get('/moderation/{ranking}', [ModerationController::class, 'show'])->name('admin.moderation.show');
    Route::post('/moderation/{ranking}/approve', [ModerationController::class, 'approveRanking'])->name('admin.moderation.approve');
    Route::post('/moderation/{ranking}/reject', [ModerationController::class, 'rejectRanking'])->name('admin.moderation.reject');
    Route::post('/moderation/images/{image}/approve', [ModerationController::class, 'approveImage'])->name('admin.moderation.image.approve');
    Route::post('/moderation/images/{image}/reject', [ModerationController::class, 'rejectImage'])->name('admin.moderation.image.reject');
});

require __DIR__.'/auth.php';