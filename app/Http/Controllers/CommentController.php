<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Método para crear un comentario
    public function store(Request $request, Video $video)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $comment = $video->comments()->create([
            'user_id' => $request->user()->id, // ID del usuario autenticado
            'content' => $request->text,
        ]);

        return response()->json([
            'message' => 'Comentario creado exitosamente.',
            'comment' => $comment->load('user'), // Carga la relación con el usuario
        ], 201);
    }

    // Método para aprobar un comentario
    public function approve(Comment $comment)
    {
        $this->authorize('approve', $comment); // Asegura que el usuario tenga permiso para aprobar

        $comment->update([
            'is_approved' => true, // Cambia el estado del comentario a aprobado
        ]);

        return response()->json([
            'message' => 'Comentario aprobado exitosamente.',
            'comment' => $comment,
        ]);
    }

    // Método para rechazar un comentario
    public function decline(Comment $comment)
    {
        $this->authorize('decline', $comment); // Asegura que el usuario tenga permiso para rechazar

        $comment->update([
            'is_approved' => false, // Cambia el estado del comentario a rechazado
        ]);

        return response()->json([
            'message' => 'Comentario rechazado exitosamente.',
            'comment' => $comment,
        ]);
    }
}

