<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('courses', [CourseController::class, 'apiIndex']);
    Route::get('courses/{id}', [CourseController::class, 'apiShow']);
    Route::post('courses/{id}/register', [UserCourseController::class, 'apiRegister']);
    Route::get('categories', [CategoryController::class, 'index']);

    Route::get('user/{id}/courses', [UserCourseController::class, 'getUserCourses']);
    Route::post('/videos/{video}/like', [VideoController::class, 'toggleLike']);

    Route::post('/video/{video}/comments', [CommentController::class, 'store'])->name('api.comments.store');
    Route::post('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('api.comments.approve');
    Route::post('/comments/{comment}/decline', [CommentController::class, 'decline'])->name('api.comments.decline');
});


