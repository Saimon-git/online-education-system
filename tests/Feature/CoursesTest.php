<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('can list courses', function () {
    // Crear datos de prueba
    Course::factory()->count(5)->create();
    Role::create(['name' =>'admin']);
    $admin = User::factory()->create()->assignRole('admin');
    actingAs($admin);
    $response = $this->getJson('/api/courses');
    $response->assertStatus(200)
        ->assertJsonCount(5, 'courses')
        ->assertJsonStructure([
            'courses' => [
                '*' => ['id', 'name', 'description', 'category_id', 'created_at', 'updated_at'],
            ],
        ]);
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


