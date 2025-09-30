<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return back()->with('error', 'Você não pode seguir a si mesmo.');
        }

        auth()->user()->following()->attach($user->id);
        return back()->with('success', 'Você agora está seguindo ' . $user->name);
    }

    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);
        return back()->with('success', 'Você deixou de seguir ' . $user->name);
    }
}
