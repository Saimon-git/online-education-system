<?php

namespace App\Livewire;

use App\Models\Video;
use Livewire\Component;

class CourseVideos extends Component
{
    public $videos = [];
    public $userId;

    public function mount($videos)
    {
        $this->videos = $videos;

        // Determinar si el usuario ha completado o le ha dado like a los videos
        $this->processVideos();
    }

    public function toggleLike($videoId)
    {
        $video = Video::find($videoId);
        $user = auth()->user();

        if ($video->likedByUsers()->where('user_id', $user->id)->exists()) {
            $video->likedByUsers()->detach($user->id);
        } else {
            $video->likedByUsers()->attach($user->id);
        }

        $this->processVideos();
    }

    public function processVideos()
    {
        $user = auth()->user();

        foreach ($this->videos as &$video) {
            // Verificar si el usuario actual ha completado el video
            $video['is_completed'] = $video['completedByUsers']
                ->contains('id', $user->id);

            // Verificar si el usuario ha dado like al video
            $video['liked'] = $video['likedByUsers']
                ->contains('id', $user->id);

        }
    }

    public function render()
    {
        return view('livewire.course-videos');
    }
}

