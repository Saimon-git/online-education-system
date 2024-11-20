<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $videos = Video::all();

        // Crea comentarios para videos
        foreach ($videos as $video) {
            Comment::factory()->count(3)->create([
                'user_id' => $users->random()->id,
                'video_id' => $video->id,
            ]);
        }
    }
}
