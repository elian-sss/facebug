<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $posts = $request->user()->feed()->with('user', 'likes', 'comments')->latest()->get();
        return Inertia::render('Feed', [
            'posts' => $posts,
        ]);
    }
}
