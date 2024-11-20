<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'videos' => 'required|array',
            'videos.*.title' => 'required|string|max:255',
            'videos.*.url' => 'required|url',
            'videos.*.duration' => 'required|string',
        ]);

        foreach ($request->videos as $videoData) {
            $course->videos()->create($videoData);
        }

        return response()->json(['message' => 'Videos agregados exitosamente'], 201);
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
            ->with('course','comments.user','completedByUsers')
            ->where('title', $url)
            ->first();

        if (!$video) {
            abort(404); // Video no encontrado, retornar error 404
        }
        // Determinar si el video está completado por el usuario actual
        $is_completed = $video->completedByUsers()->where('user_id', auth()->id())->exists();
        if ($is_completed) $video->is_completed = true;

        return Inertia::render('Videos/Show', [
            'video' => $video,
            'is_completed' => $is_completed,
        ]);

        if(!$video){
            abort(404); // Video no encontrado, retornar error 404
        }
        $is_user = auth()->user()->hasRole('user');

        return Inertia::render('Videos/Show',['video' => $video,'is_user' => $is_user]);
    }

    public function markAsCompleted(Request $request, Video $video)
    {
        $user = $request->user();

        // Verificar si el usuario ya completó el video
        if ($user->completedVideos()->where('video_id', $video->id)->exists()) {
            return response()->json(['message' => 'Video already marked as completed'], 400);
        }

        // Marcar el video como completado
        $user->completedVideos()->attach($video->id);

        // Calcular el porcentaje de curso completado
        $course = $video->course;
        $totalVideos = $course->videos()->count();
        $completedVideos = $user->completedVideos()->where('course_id', $course->id)->count();
        $progress = ($completedVideos / $totalVideos) * 100;
        //retornar solo 2 decimales en el valor del progreso
        $progress = round($progress, 2);

        // Actualizar el progreso del curso
        $user->courses()->updateExistingPivot($course->id, ['progress' => $progress]);

        return response()->json([
            'message' => 'Video marked as completed',
            'progress' => $progress,
        ]);

        // Actualizar el progreso del curso
        $user->courses()->updateExistingPivot($course->id, ['progress' => $progress]);
        //Aumentar el numero de views del video
        $video->increment('views');

        return response()->json([
            'message' => 'Video marked as completed',
            'progress' => $progress,
        ]);
    }
}
