<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\VideoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard',[
        'isAdmin' => auth()->user()->isAdmin(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('courses', CourseController::class);
    Route::resource('videos', VideoController::class);
    Route::post('courses/{course}/register', [UserCourseController::class, 'register']);
    Route::get('courses/{course}/show-video/{url}', [VideoController::class, 'showVideo'])->name('show.video');

    Route::get('/comments', function (){
        $comments = \App\Models\Comment::where('is_approved',false)
            ->with('user')
            ->get();
        $is_user = auth()->user()->hasRole('user');
        return Inertia::render('Comments',[
            'all_comments' => $comments->toArray(),
            'is_user' => $is_user,
            'isAdmin' => auth()->user()->isAdmin(),
        ]);
    });
});


require __DIR__.'/auth.php';
