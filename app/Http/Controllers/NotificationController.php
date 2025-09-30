<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Marca todas as notificações NÃO LIDAS do usuário como LIDAS.
        $user->unreadNotifications->markAsRead();

        // 2. Busca todas as notificações (agora já lidas) de forma paginada.
        $notifications = $user->notifications()->paginate(15);

        return inertia('notifications/index', [
            'notifications' => $notifications,
        ]);
    }
}
