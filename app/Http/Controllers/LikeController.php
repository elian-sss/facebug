<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
            'post_id' => 'required|exists:posts,id',
            ]);

            $like = $request->user()->likes()->where('post_id', $request->post_id)->first();

            if ($like) {
                $like->delete();
                return response()->json(['message' => 'Post descurtido'], 200);
            } else {
                $request->user()->likes()->create([
                    'post_id' => $request->post_id,
                ]);
                return response()->json(['message' => 'Post curtido'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao processar o like: ' . $e->getMessage()], 500);
        }
        
    }
}
