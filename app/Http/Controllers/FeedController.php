<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('feed/index', [
            'posts' => $posts,
        ]);
    }
}
