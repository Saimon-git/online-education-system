<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('renders the course list component', function () {
    Role::create(['name' => 'user']);
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::actingAs($user)
        ->test(\App\Livewire\CourseList::class, [
            'isUser' => $user->hasRole('user'), // O false si no es usuario autenticado
            'userId' => $user->id,
            'myCourses' => false
        ])
        ->assertSee('Listado de Cursos');
});

it('filters courses by name', function () {
    Course::factory()->create(['name' => 'Mathematics']);
    Course::factory()->create(['name' => 'Physics']);

    Role::create(['name' => 'user']);
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::actingAs($user)
        ->test(\App\Livewire\CourseList::class, [
            'isUser' => $user->hasRole('user'), // O false si no es usuario autenticado
            'userId' => $user->id,
            'myCourses' => false
        ])
        ->set('filters.name', 'Math')
        ->assertSee('Mathematics')
        ->assertDontSee('Physics');
});

it('registers a user to a course', function () {
    Role::create(['name' => 'user']);
    $user = User::factory()->create();
    $user->assignRole('user');
    $course = Course::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\CourseList::class, [
            'isUser' => $user->hasRole('user'), // O false si no es usuario autenticado
            'userId' => $user->id,
            'myCourses' => false
        ])
        ->call('register', $course->id)
        ->assertDispatched('registered')
        ->assertSee('Te has registrado correctamente en el curso.');
});

