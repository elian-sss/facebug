<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public User $commenter,
        public Comment $comment,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'comment_id' => $this->comment->id,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "{$this->commenter->name} comentou no seu post: \"{$this->post->title}\"",
            'url' => route('posts.show', $this->post->id),
        ]);
    }
}
