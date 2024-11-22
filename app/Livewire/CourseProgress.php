<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CourseProgress extends Component
{
    public $users = [];

    public function mount($courseId)
    {
        // Cargar los usuarios y su progreso en el curso
        $this->users = Course::with(['users', 'users.completedVideos'])
            ->findOrFail($courseId)
            ->users
            ->map(function ($user) {
                // Calcular el progreso del usuario y obtener el último video completado
                $user->progress = $user->pivot->progress ?? 0;
                $user->last_completed_video = $user->completedVideos->last();
                return $user;
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.course-progress');
    }
}

