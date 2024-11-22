<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\User;
use App\Models\Comment;
use App\Models\Video;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('renders approved comments only for users', function () {
    Role::create(['name' => 'user']);
    $user = User::factory()->create();
    $user->assignRole('user');
    $video = Video::factory()->create();
    Comment::factory()->count(2)->create(['video_id' => $video->id, 'is_approved' => true]);
    Comment::factory()->count(1)->create(['video_id' => $video->id, 'is_approved' => false]);

    Livewire::actingAs($user)
    ->test(\App\Livewire\CourseComments::class, [
        'videoId' => $video->id,
        'isUser' => true,
    ])
        ->assertSee('Comentarios')
        ->assertSee(Comment::where('is_approved', true)->first()->content)
        ->assertDontSee(Comment::where('is_approved', false)->first()->content);
});

it('approves a comment as an admin', function () {
    Role::create(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $video = Video::factory()->create();
    $comment = Comment::factory()->create(['is_approved' => false]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\CourseComments::class,[
            'videoId' => $video->id,
            'isUser' => false,
        ])
        ->call('approveComment', $comment->id)
        ->assertDispatched('flashMessageUpdated')
        ->assertSee('Comentario aprobado correctamente.');
});

