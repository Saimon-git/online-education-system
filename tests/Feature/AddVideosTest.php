<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\Course;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('adds videos to a course', function () {
    Role::create(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $course = Course::factory()->create();

    Livewire::actingAs($admin)
    ->test(\App\Livewire\AddVideos::class, ['courseId' => $course->id])
        ->set('videos.0.title', 'First Video')
        ->set('videos.0.url', 'https://youtube.com/example')
        ->set('videos.0.duration', '5:32')
        ->call('saveVideos')
        ->assertDispatched('storeVideos');

    $this->assertDatabaseHas('videos', [
        'title' => 'First Video',
        'course_id' => $course->id,
    ]);
});

