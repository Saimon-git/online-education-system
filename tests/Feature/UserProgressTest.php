<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calculates user progress for a course', function () {
    // Crear usuario y curso con 4 videos
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $videos = Video::factory()->count(4)->create(['course_id' => $course->id]);

    // Simular que el usuario ha completado 2 de los 4 videos
    $user->completedVideos()->attach($videos->take(2)->pluck('id'));

    // Autenticar usuario
    $this->actingAs($user);

    // Llamar a la ruta para obtener el progreso
    $response = $this->getJson("/api/courses/{$course->id}/progress");

    // Verificar el progreso
    $response->assertStatus(200)
        ->assertJson([
            'progress' => 50, // (2 de 4 videos completados)
        ]);
});

