<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
            'id' => 'required|exists:posts,id',
            ]);

            $like = $request->user()->likes()->where('post_id', $request->id)->first();

            if ($like) {
                $like->delete();
                return back()->with('success',  'Post descurtido');
            } else {
                $request->user()->likes()->create([
                    'post_id' => $request->id,
                ]);
                return back()->with('success', 'Post curtido');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar o like: ' . $e->getMessage()], 500);
        }

    }
}
