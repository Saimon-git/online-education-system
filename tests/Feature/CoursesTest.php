<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list courses', function () {
    $response = $this->get('/api/courses');
    $response->assertStatus(200);
});

it('admin can create courses', function () {
    $admin = User::factory()->create()->assignRole('admin');
    $this->actingAs($admin)->post('/courses', [
        'name' => 'Nuevo Curso',
        'description' => 'Descripción del curso',
        'category' => 'Tecnología',
        'age_group' => '14-16',
    ])->assertStatus(201);
});

