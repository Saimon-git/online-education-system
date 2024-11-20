<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('can list courses with pagination', function () {
    // Crear datos de prueba
    Course::factory()->count(5)->create();
    Role::create(['name' => 'admin']);
    $admin = User::factory()->create()->assignRole('admin');

    // Autenticar como administrador
    actingAs($admin);

    // Realizar solicitud a la API
    $response = $this->getJson('/api/courses');

    // Verificar la respuesta
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'description', 'category_id', 'created_at', 'updated_at'],
            ],
            'current_page',
            'last_page',
            'total',
            'per_page',
            'next_page_url',
            'prev_page_url',
        ])
        ->assertJsonPath('total', 5); // Asegurar que el total de cursos es correcto
});


it('admin can create courses', function () {
    Role::create(['name' =>'admin']);
    $admin = User::factory()->create()->assignRole('admin');
    $category = Category::factory()->create();
    actingAs($admin);
    post('/courses', [
        'name' => 'Nuevo Curso',
        'description' => 'Descripción del curso',
        'category_id' => $category->id,
        'age_group' => '14-16',
    ])->assertStatus(201);
});

it('allows a user to register to a course', function () {
    // Crear curso y usuario
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    // Hacer la solicitud
    $response = $this->postJson("/api/courses/{$course->id}/register");

    // Verificar la respuesta
    $response->assertStatus(201)
        ->assertJson([
            'message' => 'You have successfully registered in the course',
        ]);

    // Verificar que el usuario esté registrado en el curso
    $this->assertDatabaseHas('user_courses', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('prevents duplicate registration to the same course', function () {
    // Crear curso y usuario
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    // Registrar al curso
    $this->postJson("/api/courses/{$course->id}/register");

    // Intentar registrarse nuevamente
    $response = $this->postJson("/api/courses/{$course->id}/register");

    // Verificar la respuesta
    $response->assertStatus(400)
        ->assertJson([
            'message' => 'You are already registered in this course',
        ]);
});

it('prevents non-admin users from creating courses', function () {
    // Crear un usuario normal
    $user = \App\Models\User::factory()->create();
    Category::factory()->create();

    // Autenticar como usuario normal
    actingAs($user);

    // Intentar crear un curso
    $response = $this->postJson('/api/courses', [
        'name' => 'Curso de Prueba',
        'description' => 'Descripción del curso',
        'category_id' => 1,
        'age_group' => '5-8',
    ]);

    // Verificar acceso denegado
    $response->assertStatus(403);
});

it('returns associated videos when retrieving a course', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    // Crear un curso y asociar videos
    $course = \App\Models\Course::factory()->create();
    $videos = \App\Models\Video::factory()->count(3)->create(['course_id' => $course->id]);

    // Obtener detalles del curso
    $response = $this->getJson("/api/courses/{$course->id}");

    // Verificar que los videos están en la respuesta
    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'description',
            'videos' => [
                '*' => ['id', 'title', 'url', 'duration'],
            ],
        ])
        ->assertJsonCount(3, 'videos');
});

it('updates course progress when videos are completed', function () {
    // Crear usuario y curso con 3 videos
    $user = \App\Models\User::factory()->create();
    $course = \App\Models\Course::factory()->create();
    $videos = \App\Models\Video::factory()->count(3)->create(['course_id' => $course->id]);

    // Asociar usuario al curso
    $user->courses()->attach($course->id, ['progress' => 0]);

    // Autenticar usuario
    actingAs($user);

    // Marcar un video como completado
    $this->postJson("/api/videos/{$videos[0]->id}/complete");

    // Verificar que el progreso es 33.33%
    $this->assertDatabaseHas('user_courses', [
        'user_id' => $user->id,
        'course_id' => $course->id,
        'progress' => 33.33,
    ]);
});





