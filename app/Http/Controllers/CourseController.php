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

    public function create()
    {
        $is_user = auth()->user()->hasRole('user');
        $user_id = auth()->id();
        return Inertia::render('Courses/Create',['is_user' => $is_user, 'user_id' => $user_id]);
    }

    public function store(Request $request)
    {
        //si el auth user no es admin no puede crear cursos
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'No tienes permisos para crear cursos'], 403);
        }

        // Valida los datos del curso
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'age_group' => 'required|in:5-8,9-13,14-16,16+',
        ]);

        $course = Course::create($validated);

        return response()->json(['message' => 'Curso creado exitosamente!', 'course' => $course], 201);
        // Valida los datos del curso
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'age_group' => 'required|in:5-8,9-13,14-16,16+',
        ]);


        $course = Course::create($validated);

        return response()->json(['message' => 'Curso creado exitosamente!', 'course' => $course], 201);
    }

    public function show(Course $course)
    {
        $is_user = auth()->user()->hasRole('user');
        return Inertia::render('Courses/Show',['course' => $course->id, 'is_user' => $is_user]);
    }


    public function apiIndex(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        // Devuelve todos los cursos con las categorías y videos relacionados
        $courses = Course::with('category', 'videos')->paginate();

        return response()->json( $courses , 200);
    }

    public function apiShow($id)
    {
        // Encuentra el curso por ID o devuelve un error 404
        $course = Course::with('category', 'videos.likedByUsers','videos.course','videos.completedByUsers','users.completedVideos')->findOrFail($id);

        return response()->json($course, 200);
    }

    public function getProgress($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();

        $totalVideos = $course->videos()->count();
        $completedVideos = $course->videos()
            ->whereHas('completedByUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

        $progress = ($completedVideos / $totalVideos) * 100;

        return response()->json(['progress' => round($progress, 2)], 200);
    }
}

