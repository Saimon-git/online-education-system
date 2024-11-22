<?php

namespace App\Livewire;

use App\Models\Video;
use App\Models\Course;
use Livewire\Component;

class VideoDetail extends Component
{
    public $video;
    public $courseId;
    public $isUser;
    public $embedUrl;
    public $isCompleted;

    public function mount(Video $video, $courseId, $isUser = false)
    {
        $this->video = $video;
        $this->courseId = $courseId;
        $this->isUser = $isUser;
        $this->isCompleted = $this->checkCompletion();

        // Generar la URL embebible del video
        $this->embedUrl = $this->generateEmbedUrl($video->url);
    }

    public function generateEmbedUrl($url)
    {
        if (strpos($url, 'youtube.com/watch') !== false) {
            $videoId = explode('v=', $url)[1];
            $videoId = explode('&', $videoId)[0];
            return "https://www.youtube.com/embed/{$videoId}";
        }
        return $url;
    }

    public function checkCompletion()
    {
        $user = auth()->user();
        return $user && $this->video->completedByUsers->contains('id', $user->id);
    }

    public function markAsCompleted()
    {
        $user = auth()->user();

        if ($this->isCompleted) {
            session()->flash('message', 'Este video ya ha sido marcado como completado.');
            return;
        }

        $this->video->completedByUsers()->attach($user->id,[
            'course_id' => $this->courseId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->isCompleted = true;

        // Recalcular el progreso del curso
        $progress = $this->updateCourseProgress($user);

        session()->flash('message', "¡Video marcado como completado! Progreso: {$progress}%");
        $this->dispatch('storeCourse');
        return redirect()->to("/courses/{$this->courseId}");
    }

    public function updateCourseProgress($user)
    {
        $course = Course::find($this->courseId);

        $totalVideos = $course->videos->count();
        $completedVideos = $course->videos()->whereHas('completedByUsers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        $progress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100, 2) : 0;

        $course->users()->updateExistingPivot($user->id, ['progress' => $progress]);

        return $progress;
    }

    public function render()
    {
        return view('livewire.video-detail');
    }
}

