<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');
Route::get('/rankings/create', [RankingController::class, 'create'])->name('rankings.create');
Route::post('/rankings', [RankingController::class, 'store'])->name('rankings.store');

Route::get('/rankings/create', [RankingController::class, 'create'])->name('rankings.create');
Route::post('/rankings', [RankingController::class, 'store'])->name('rankings.store');

Route::get('/', [RankingController::class, 'home'])->name('home');
Route::resource('rankings', RankingController::class);

Route::post('/rankings/{ranking}/vote', [RankingController::class, 'vote'])->name('rankings.vote');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
