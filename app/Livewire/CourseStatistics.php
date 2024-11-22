<?php

namespace App\Livewire;

use App\Models\Video;
use Livewire\Component;

class CourseStatistics extends Component
{
    public $videos = [];

    public function mount($videos)
    {
        // Cargar las estadísticas de los videos del curso
        $this->videos = $videos->toArray();
    }

    public function render()
    {
        return view('livewire.course-statistics');
    }
}

