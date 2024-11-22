<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Course;
use Livewire\Component;

class CreateCourse extends Component
{
    public $name;
    public $description;
    public $category_id;
    public $age_group;
    public $categories = [];
    public $ageRanges = ['5-8', '9-13', '14-16', '16+'];
    public $createdCourseId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:500',
        'category_id' => 'required|exists:categories,id',
        'age_group' => 'required|string|in:5-8,9-13,14-16,16+',
    ];

    public function mount()
    {
        // Cargar categorías al montar el componente
        $this->categories = Category::all();
    }

    public function createCourse()
    {
        $this->validate();

        // Crear el curso
        $course = Course::create([
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'age_group' => $this->age_group,
        ]);

        $this->createdCourseId = $course->id;

        // Limpiar el formulario
        //$this->reset(['name', 'description', 'category_id', 'age_group']);

        session()->flash('message', '¡Curso creado exitosamente!');
        $this->dispatch('storeCourse');
    }

    public function render()
    {
        return view('livewire.create-course');
    }
}
