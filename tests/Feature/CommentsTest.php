<?php

use App\Models\Comment;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

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

it('allows an admin to approve a comment', function () {
    Role::create(['name'=>'admin']);
    // Crear un usuario admin
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Crear un video y un comentario
    $video = Video::factory()->create();
    $comment = Comment::factory()->create([
        'video_id' => $video->id,
    ]);

    // Autenticar como administrador
    $this->actingAs($admin);

    // Aprobar el comentario
    $response = $this->postJson("/api/comments/{$comment->id}/approve");

    // Verificar la respuesta
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Comentario aprobado exitosamente.',
        ]);

    // Verificar en la base de datos
    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'is_approved' => true,
    ]);
});

it('allows an admin to decline a comment', function () {
    Role::create(['name'=>'admin']);
    // Crear un usuario admin
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Crear un video y un comentario
    $video = Video::factory()->create();
    $comment = Comment::factory()->create([
        'video_id' => $video->id,
    ]);

    // Autenticar como administrador
    $this->actingAs($admin);

    // Declinar el comentario
    $response = $this->postJson("/api/comments/{$comment->id}/decline");

    // Verificar la respuesta
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Comentario rechazado exitosamente.',
        ]);

    // Verificar en la base de datos
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});

it('prevents non-admin users from approving a comment', function () {
    // Crear un usuario normal
    $user = User::factory()->create();

    // Crear un comentario
    $comment = Comment::factory()->create(['is_approved' => false]);

    // Autenticar como usuario normal
    $this->actingAs($user);

    // Intentar aprobar el comentario
    $response = $this->postJson("/api/comments/{$comment->id}/approve");

    // Verificar que la acción está prohibida
    $response->assertStatus(403);
});

it('prevents non-admin users from declining a comment', function () {
    // Crear un usuario normal
    $user = User::factory()->create();

    // Crear un comentario
    $comment = Comment::factory()->create();

    // Autenticar como usuario normal
    $this->actingAs($user);

    // Intentar declinar el comentario
    $response = $this->postJson("/api/comments/{$comment->id}/decline");

    // Verificar que la acción está prohibida
    $response->assertStatus(403);
});
