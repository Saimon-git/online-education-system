<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function approve(User $user, Comment $comment)
    {
        return $user->isAdmin(); // Cambia según tu lógica
    }

    // Permitir que solo administradores puedan rechazar comentarios
    public function decline(User $user, Comment $comment)
    {
        return $user->isAdmin(); // Cambia según tu lógica
    }
}
