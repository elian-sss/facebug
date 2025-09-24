<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = Post::where('user_id', $user->id)
            ->with(['user', 'likes', 'comments.user'])
            ->latest()
            ->paginate(10);

        return inertia('profile/show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
