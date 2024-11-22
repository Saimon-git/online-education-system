<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Course;
use App\Models\Video;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('displays course details', function () {
    $user = User::factory()->create();
    $course = Course::factory()->hasVideos(5)->create();

    Livewire::actingAs($user)
    ->test(\App\Livewire\CourseDetails::class, [
        'courseId' => $course->id,
        'isUser' => false,
    ])
        ->assertSee($course->name)
        ->assertSee($course->description)
        ->assertSee('Continuar curso');
});

it('calculates user progress correctly', function () {
    Role::create(['name' => 'user']);
    $user = User::factory()->create();
    $user->assignRole('user');
    $course = Course::factory()->hasVideos(5)->create();
    $user->courses()->attach($course->id, ['progress' => 0]);
    $completedVideos = $course->videos->take(3);

    foreach ($completedVideos as $video) {
        $user->completedVideos()->attach($video->id, ['course_id' => $course->id]);
    }
    // Calcular el porcentaje de curso completado
    $totalVideos = $course->videos->count();
    $completedVideos = $course->videos()->whereHas('completedByUsers', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->count();

    $progress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100, 2) : 0;

    // Actualizar el progreso del curso
    $user->courses()->updateExistingPivot($course->id, ['progress' => $progress]);


    Livewire::actingAs($user)
        ->test(\App\Livewire\CourseDetails::class, [
            'courseId' => $course->id,
            'isUser' => true,
        ])
        ->assertSet('userProgress', 60); // 3 de 5 videos completados = 60%
});
