<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FeedController;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('dashboard', function () {
    return redirect()->route('feed.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('feed', [FeedController::class, 'index'])->name('feed.index');

    Route::resource('posts', \App\Http\Controllers\PostController::class)->except(['create', 'edit']);

    Route::resource('comments', \App\Http\Controllers\CommentController::class)->only(['store', 'update', 'destroy']);

    Route::post('likes', [\App\Http\Controllers\LikeController::class, 'store'])->name('likes.store');

    Route::get('/users/{user:name}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');

    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
