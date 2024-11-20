<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $is_user = auth()->user()->hasRole('user');
        $user_id = auth()->id();
        return Inertia::render('Courses/Index',['is_user' => $is_user, 'user_id' => $user_id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'age_group' => 'required|in:5-8,9-13,14-16,16+',
        ]);

        return Course::create($validated);
    }

    public function show(Course $course)
    {
        $is_user = auth()->user()->hasRole('user');
        return Inertia::render('Courses/Show',['course' => $course->id, 'is_user' => $is_user]);
    }


    public function apiIndex()
    {
        // Devuelve todos los cursos con las categorías y videos relacionados
        $courses = Course::with('category', 'videos')->get();
        $token = auth()->user()->tokens->first()->token;

        return response()->json([
            'courses' =>$courses,
            'token' => $token, // Token de autenticación para usar en las API
            ], 200);
    }

    public function apiShow($id)
    {
        // Encuentra el curso por ID o devuelve un error 404
        $course = Course::with('category', 'videos.likedByUsers','videos.course','users')->findOrFail($id);

        return response()->json($course, 200);
    }
}

