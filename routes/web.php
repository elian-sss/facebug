<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
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
    Route::post('/users/{user}/follow', [\App\Http\Controllers\FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [\App\Http\Controllers\FollowController::class, 'destroy'])->name('users.unfollow');

    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/with/{user}', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/chat/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
