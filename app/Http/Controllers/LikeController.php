<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\NewLikeNotification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:posts,id',
            ]);

            $user = $request->user();
            $post = Post::findOrFail($request->id); //
            $like = $user->likes()->where('post_id', $post->id)->first();

            if ($like) {
                $like->delete();
                return back()->with('success', 'Post descurtido');
            } else {
                $user->likes()->create([
                    'post_id' => $post->id,
                ]);

                if ($user->id !== $post->user_id) {
                    $post->user->notify(new NewLikeNotification($user, $post));
                }

                return back()->with('success', 'Post curtido');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao processar o like: ' . $e->getMessage());
        }
    }
}
