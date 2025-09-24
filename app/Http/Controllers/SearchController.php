<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::query()
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->with('user', 'likes', 'comments.user')
            ->latest()
            ->paginate(5, ['*'], 'posts_page');

        $users = User::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->paginate(10, ['*'], 'users_page');

        $comments = Comment::query()
            ->where('content', 'LIKE', "%{$query}%")
            ->with(['user', 'post'])
            ->latest()
            ->paginate(10, ['*'], 'comments_page');

        return inertia('search/index', [
            'query' => $query,
            'posts' => $posts,
            'users' => $users,
            'comments' => $comments,
        ]);
    }
}
