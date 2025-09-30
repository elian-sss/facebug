<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message
    ) {}

    // Define o canal privado para a transmissão
    public function broadcastOn(): array
    {
        // Apenas os participantes desta conversa receberão o evento
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    // Define os dados que serão enviados para o frontend
    public function broadcastWith(): array
    {
        return ['message' => $this->message->load('user')];
    }
}
