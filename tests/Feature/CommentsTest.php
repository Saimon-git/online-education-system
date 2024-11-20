<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('allows a user to create a comment', function () {
    // Crear curso y usuario
    $course = Course::factory()->create();
    $video = Video::factory()->create(['course_id' => $course->id]);
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear comentario
    $response = $this->postJson("/api/video/{$video->id}/comments", [
        'text' => 'Este es un comentario de prueba.',
    ]);

    // Verificar la respuesta
    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Comentario creado exitosamente.',
        ]);

    // Verificar en la base de datos
    $this->assertDatabaseHas('comments', [
        'video_id' => $video->id,
        'user_id' => $user->id,
        'content' => 'Este es un comentario de prueba.',
        'is_approved' => false,
    ]);
});
