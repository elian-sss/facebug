<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $posts = Posts::with(['user', 'likes', 'comments.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return Inertia::render('Feed', [
            'posts' => $posts,
        ]);
    }
}
