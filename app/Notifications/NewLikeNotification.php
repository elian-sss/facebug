<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // Importe
use Illuminate\Notifications\Messages\BroadcastMessage; // Importe
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification implements ShouldBroadcast // Implemente
{
    use Queueable;

    public function __construct(
        public User $liker,
        public Post $post
    ) {}

    // Define os canais de entrega (banco de dados E broadcast)
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    // Define o que será armazenado no banco de dados
    public function toArray(object $notifiable): array
    {
        return [
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
        ];
    }

    // Define o que será enviado via WebSocket (Reverb)
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "{$this->liker->name} curtiu seu post: \"{$this->post->title}\"",
            'liker_name' => $this->liker->name,
            'post_title' => $this->post->title,
            'url' => route('posts.show', $this->post->id), // Supondo que você tenha essa rota
        ]);
    }
}
