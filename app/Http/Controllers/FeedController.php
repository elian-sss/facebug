<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $followingIds = $user->following()->pluck('users.id');

        $posts = Post::whereIn('user_id', $followingIds->push($user->id))
            ->with(['user', 'likes', 'comments.user'])
            ->latest()
            ->get();

        $suggestedUsers = collect(); // Inicia uma coleção vazia

        // Se o feed de posts estiver vazio E o usuário não seguir ninguém, busca sugestões
        if ($user->following()->count() === 0) {
            $suggestedUsers = User::where('id', '!=', $user->id)
                ->inRandomOrder()
                ->limit(5)
                ->get();
        }

        return inertia('feed/index', [
            'posts' => $posts,
            'suggestedUsers' => $suggestedUsers, // Envia a nova prop
        ]);
    }
}
