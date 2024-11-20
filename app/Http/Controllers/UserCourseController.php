<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class UserCourseController extends Controller
{
    public function register(Request $request, $courseId)
    {
        $user = auth()->user();
        $user->courses()->attach($courseId, ['progress' => 0]);

        return response()->json(['message' => 'Course registered successfully']);
    }

    public function progress($courseId)
    {
        $user = auth()->user();
        $progress = $user->courses()->where('course_id', $courseId)->first()->pivot->progress;

        return response()->json(['progress' => $progress]);
    }

    public function apiRegister(Request $request, $id)
    {
        $user = auth()->user(); // Obtiene el usuario autenticado

        // Verifica si el curso existe
        $course = Course::findOrFail($id);

        // Verifica si el usuario ya está registrado en el curso
        if ($user->courses->count() > 0 && $user->courses->contains($course)) {
            return response()->json(['message' => 'You are already registered in this course'], 400);
        }

        // Registra al usuario en el curso con progreso inicial 0
        $user->courses()->attach($course->id, ['progress' => 0]);

        return response()->json([
            'message' => 'You have successfully registered in the course',
            'course' => $course,
        ], 201);
    }

    public function getUserCourses(Request $request,$id)
    {
        // Obtiene el usuario autenticado
        $user = auth()->user();

        // Obtiene los cursos en los que el usuario está registrado
        $courses = $user->courses()->with('category', 'videos')->get();

        // Retorna la lista de cursos
        return response()->json([
            'courses' => $courses,
        ]);
    }
}
