<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
            'content' => 'required|string|max:1000',
            'post_id' => 'required|exists:posts,id',
            ]);

            $comment = $request->user()->comments()->create([
                'content' => $request->content,
                'post_id' => $request->post_id,
            ]);

            return back()->with('success', 'Comentário adicionado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao adicionar o comentário: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Comment $comment)
    {
        try {
            if ($request->user()->id !== $comment->user_id) {
                return back()->with('error', 'Você não tem permissão para editar este comentário.');
            }

            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment->update([
                'content' => $request->content,
            ]);

            return back()->with('success', 'Comentário atualizado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar o comentário: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Comment $comment)
    {
        try {
            if ($request->user()->id !== $comment->user_id) {
                return back()->with('error', 'Você não tem permissão para deletar este comentário.');
            }

            $comment->delete();
            return back()->with('success', 'Comentário deletado com sucesso.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao deletar o comentário: ' . $e->getMessage());
        }
    }
}
