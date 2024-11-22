<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CourseDetails extends Component
{
    public $courseId;
    public $isUser;
    public $course;
    public $userProgress = 0;

    public function mount($courseId, $isUser)
    {
        $this->courseId = $courseId;
        $this->isUser = $isUser;

        // Cargar los detalles del curso al montar el componente
        $this->fetchCourseDetails();
    }

    public function fetchCourseDetails()
    {
        $this->course = Course::with('category', 'videos.likedByUsers','videos.course','videos.completedByUsers','users.completedVideos')
            ->findOrFail($this->courseId);

        $this->calculateUserProgress();
    }

    public function calculateUserProgress()
    {
        $currentUser = auth()->user();

        // Buscar el progreso del usuario actual
        $user = $this->course->users->firstWhere('id', $currentUser->id);
        if ($user) {
            $this->userProgress = $user->pivot->progress ?? 0;
        }
    }

    public function continueCourse()
    {
        // Encontrar el primer video no completado
        $currentUser = auth()->user();
        $firstIncompleteVideo = $this->course->videos->firstWhere(
            fn($video) => !$video->completedByUsers->contains('id', $currentUser->id)
        );

        if ($firstIncompleteVideo) {
            return redirect()->to("/courses/{$this->courseId}/show-video/{$firstIncompleteVideo->title}");
        } else {
            session()->flash('message', '¡Ya has completado todos los videos de este curso!');
        }
    }

    public function render()
    {
        return view('livewire.course-details');
    }
}

