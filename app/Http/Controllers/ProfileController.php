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

        // Verifica se o usuário autenticado segue o usuário do perfil
        $isFollowed = auth()->user() ? auth()->user()->following->contains($user->id) : false;

        return inertia('profile/show', [
            'user' => $user->loadCount(['posts', 'followers', 'following']),
            'posts' => $posts,
            'isFollowed' => $isFollowed, // Envia o status para o frontend
        ]);
    }
}
