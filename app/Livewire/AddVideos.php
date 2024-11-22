<?php

namespace App\Livewire;

use App\Models\Video;
use Livewire\Component;

class AddVideos extends Component
{
    public $courseId;
    public $videos = [
        [
            'title' => '',
            'url' => '',
            'duration' => '',
        ],
    ];

    protected $rules = [
        'videos.*.title' => 'required|string|max:255',
        'videos.*.url' => 'required|url',
        'videos.*.duration' => 'required|string|max:10',
    ];

    public function addVideo()
    {
        $this->videos[] = [
            'title' => '',
            'url' => '',
            'duration' => '',
        ];
    }

    public function removeVideo($index)
    {
        unset($this->videos[$index]);
        $this->videos = array_values($this->videos); // Reindexar el array
    }

    public function saveVideos()
    {
        $this->validate();

        foreach ($this->videos as $video) {
            Video::create([
                'course_id' => $this->courseId,
                'title' => $video['title'],
                'url' => $video['url'],
                'duration' => $video['duration'],
            ]);
        }

        session()->flash('message', '¡Videos guardados exitosamente!');
        $this->dispatch('storeVideos');

        // Redirigir al detalle del curso
        return redirect()->route('courses.show', $this->courseId);
    }

    public function render()
    {
        return view('livewire.add-videos');
    }
}
