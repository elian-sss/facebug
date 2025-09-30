<?php

namespace App\Http\Controllers;

use App\Events\NewMessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        // Garante que o usuário atual participa da conversa
        abort_if(!$conversation->users->contains(auth()->id()), 403);

        $request->validate(['body' => 'required|string']);

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        // Dispara o evento e envia para os outros participantes
        broadcast(new NewMessageSent($message))->toOthers();

        return response()->json($message->load('user'));
    }
}
