<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $user)
    {
        $follower = auth()->user();

        if ($follower->id === $user->id) {
            return back()->with('error', 'Você não pode seguir a si mesmo.');
        }

        $follower->following()->attach($user->id);

        $user->notify(new NewFollowerNotification($follower));

        return back()->with('success', 'Você agora está seguindo ' . $user->name);
    }

    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);
        return back()->with('success', 'Você deixou de seguir ' . $user->name);
    }
}
