<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        return Video::create($validated);
    }

    public function toggleLike(Video $video, Request $request)
    {
        $user = $request->user();

        // Verificar si ya le dio like
        if ($video->likedByUsers()->where('user_id', $user->id)->exists()) {
            // Si ya tiene like, eliminarlo
            $video->likedByUsers()->detach($user->id);
            $video->decrement('likes'); // Decrementar el contador de likes
            return response()->json(['message' => 'Like removed', 'likes' => $video->likes]);
        }

        // Si no tiene like, agregarlo
        $video->likedByUsers()->attach($user->id);
        $video->increment('likes'); // Incrementar el contador de likes
        return response()->json(['message' => 'Like added', 'likes' => $video->likes]);
    }

    public function showVideo($course_id, $url)
    {
        $video = Video::where('course_id', $course_id)
            ->with('course','comments.user')
            ->where('title', $url)
            ->first();

        if(!$video){
            abort(404); // Video no encontrado, retornar error 404
        }
        $is_user = auth()->user()->hasRole('user');

        return Inertia::render('Videos/Show',['video' => $video,'is_user' => $is_user]);
    }
}
