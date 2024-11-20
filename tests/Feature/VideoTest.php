<?php

use App\Models\Course;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list all videos for a course', function () {
    // Crear curso con videos
    $course = Course::factory()->create();
    Video::factory()->count(3)->create(['course_id' => $course->id]);

    $user = User::factory()->create();
    $this->actingAs($user);

    // Hacer la solicitud
    $response = $this->getJson("/api/courses/{$course->id}");


    // Verificar la respuesta
    $response->assertStatus(200);
});

it('allows a user to like a video', function () {
    // Crear video y usuario
    $video = Video::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    // Hacer la solicitud de like
    $response = $this->postJson("/api/videos/{$video->id}/like");

    // Verificar la respuesta
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Like added',
        ]);

    // Verificar en la base de datos
    $this->assertDatabaseHas('user_video_likes', [
        'user_id' => $user->id,
        'video_id' => $video->id,
    ]);
});

it('allows a user to unlike a video', function () {
    // Crear video y usuario
    $video = Video::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    // Agregar like antes de quitarlo
    $video->likedByUsers()->attach($user);

    // Hacer la solicitud de unlike
    $response = $this->postJson("/api/videos/{$video->id}/like");

    // Verificar la respuesta
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Like removed',
        ]);

    // Verificar en la base de datos
    $this->assertDatabaseMissing('user_video_likes', [
        'user_id' => $user->id,
        'video_id' => $video->id,
    ]);
});

it('associates videos to a course', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    // Crear un curso
    $course = \App\Models\Course::factory()->create();

    // Datos de videos para asociar
    $videos = [
        ['title' => 'Video 1', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'duration' => '5:00'],
        ['title' => 'Video 2', 'url' => 'https://www.youtube.com/watch?v=abc123', 'duration' => '3:30'],
    ];

    // Simular solicitud para asociar videos
    $response = $this->postJson("/api/courses/{$course->id}/videos", ['videos' => $videos]);

    // Verificar respuesta exitosa
    $response->assertStatus(201)
        ->assertJsonPath('message', 'Videos agregados exitosamente');

    // Verificar que los videos están en la base de datos
    foreach ($videos as $video) {
        $this->assertDatabaseHas('videos', [
            'course_id' => $course->id,
            'title' => $video['title'],
            'url' => $video['url'],
            'duration' => $video['duration'],
        ]);
    }
});

it('validates video data when adding to a course', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    // Crear un curso
    $course = \App\Models\Course::factory()->create();

    // Video con datos faltantes
    $videos = [
        ['title' => '', 'url' => '', 'duration' => ''],
    ];

    // Intentar agregar videos inválidos
    $response = $this->postJson("/api/courses/{$course->id}/videos", ['videos' => $videos]);

    // Verificar errores de validación
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['videos.0.title', 'videos.0.url', 'videos.0.duration']);
});



