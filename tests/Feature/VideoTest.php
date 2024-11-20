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

