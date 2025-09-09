<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'likes', 'comments.user'])->latest()->get();
        return inertia('Posts/Index', ['posts' => $posts]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            ]);

            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => $request->user()->id,
            ]);

            DB::commit();
            return back()->with('success', 'Post criado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',  'Erro ao criar o post: ' . $e->getMessage());
        }   
    }

    public function update(Request $request, Post $post)
    {
        try {
            DB::beginTransaction();
            if ($request->user()->id !== $post->user_id) {
            return back()->with('error', 'Você não tem permissão para editar este post.');
        }

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            DB::commit();
            return back()->with('success', 'Post atualizado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',  'Erro ao atualizar o post: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Post $post)
    {
        try {
            DB::beginTransaction();
            if ($request->user()->id !== $post->user_id) {
                return back()->with('error', 'Você não tem permissão para deletar este post.');
            }

            $post->delete();

            DB::commit();
            return back()->with('success', 'Post deletado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error',  'Erro ao deletar o post: ' . $e->getMessage());
        }
    }
}
