<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\VideoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth')->group(function () {

    Route::resource('courses', CourseController::class);
    Route::resource('videos', VideoController::class);
    Route::post('courses/{course}/register', [UserCourseController::class, 'register']);
    Route::get('courses/{course}/show-video/{title}', [VideoController::class, 'showVideo'])->name('show.video');
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my');
     Route::get('/comments', function () {
        $is_user = auth()->user()->hasRole('user');
        return view('comments', [
            'is_user' => $is_user,
        ]);
    })->name('comments.index');
});


require __DIR__ . '/auth.php';
