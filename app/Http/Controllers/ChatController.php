<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // Mostra a página principal do chat com a lista de conversas
    public function index()
    {
        $user = auth()->user();
        $conversations = $user->conversations()->with('users', 'messages')->get();

        return inertia('Chat/Index', [
            'conversations' => $conversations
        ]);
    }

    // Retorna os detalhes (mensagens) de uma conversa específica
    public function show(Conversation $conversation)
    {
        // Garante que o usuário atual participa da conversa
        abort_if(!$conversation->users->contains(auth()->id()), 403);

        $messages = $conversation->messages()->with('user')->latest()->paginate(20);

        return response()->json($messages);
    }

    // Inicia uma nova conversa com um usuário
    public function store(User $user)
    {
        $currentUser = auth()->user();

        // Lógica para encontrar ou criar uma conversa entre os 2 usuários
        $conversation = Conversation::whereHas('users', function ($query) use ($currentUser, $user) {
            $query->where('user_id', $currentUser->id);
        })->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([$currentUser->id, $user->id]);
        }

        return redirect()->route('chat.index', ['conversation_id' => $conversation->id]);
    }
}
