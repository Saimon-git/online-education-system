<?php

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to mark a video as completed', function () {
    // Crear usuario y video
    $user = User::factory()->create();
    $video = Video::factory()->create();

    // Autenticar usuario
    $this->actingAs($user);

    // Marcar el video como completado
    $response = $this->postJson("/api/videos/{$video->id}/complete");

    // Verificar que la respuesta es correcta
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Video marked as completed',
        ]);

    // Verificar en la base de datos
    $this->assertDatabaseHas('user_video_completions', [
        'user_id' => $user->id,
        'video_id' => $video->id,
    ]);
});

it('does not allow a user to mark a video as completed twice', function () {
    // Crear usuario y video
    $user = User::factory()->create();
    $video = Video::factory()->create();

    // Autenticar usuario
    $this->actingAs($user);

    // Marcar el video como completado por primera vez
    $user->completedVideos()->attach($video->id,[
        'course_id' => $video->course_id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Intentar completarlo nuevamente
    $response = $this->postJson("/api/videos/{$video->id}/complete");

    // Verificar que muestra un error
    $response->assertStatus(400)
        ->assertJson([
            'message' => 'Video already marked as completed',
        ]);
});

