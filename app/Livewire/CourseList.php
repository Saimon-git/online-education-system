<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;
use App\Models\Category;

class CourseList extends Component
{
    use WithPagination;

    public $filters = [
        'name' => '',
        'category_id' => '',
        'age_group' => '',
    ];

    public $ageRanges = ['5-8', '9-13', '14-16', '16+'];
    public $categories = [];
    public $isUser;
    public $userId;
    public $myCourses;

    protected $queryString = [
        'filters' => ['except' => ['name' => '', 'category_id' => '', 'age_group' => '']],
        'page' => ['except' => 1],
    ];

    public function mount($isUser, $userId,$myCourses)
    {
        $this->queryString = ['page'];
        $this->isUser = $isUser;
        $this->userId = $userId;
        $this->myCourses = $myCourses;

        // Cargar categorías al montar el componente
        $this->categories = Category::all();
    }

    public function updatedFilters($name,$value)
    {
        // Reiniciar paginación al actualizar los filtros
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.course-list', [
            'courses' => $this->getCurrentCourses()
        ]);
    }

    protected function getCurrentCourses()
    {
        if($this->myCourses){
            $query = auth()->user()->courses()->with('category');
        }else {
            $query = Course::with('category');
        }

        // Aplicar filtros
        if (!empty($this->filters['name'])) {
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['age_group'])) {
            $query->where('age_group', $this->filters['age_group']);
        }

        // Obtener los cursos paginados
        $courses = $query->paginate(10);

        // Agregar la relación `is_registered` solo si el usuario está autenticado
        if ($this->isUser && auth()->user()->courses) {
            $userCourses = auth()->user()->courses->pluck('id')->toArray();
            foreach ($courses as $course) {
                $course->is_registered = in_array($course->id, $userCourses);
            }
        }

        return $courses;
    }

    public function register($courseId)
    {
        $user = auth()->user();

        // Verifica si el usuario ya está registrado en el curso
        if ($user->courses()->where('course_id', $courseId)->exists()) {
            session()->flash('message', 'Ya estás registrado en este curso.');
            return;
        }

        // Registra al usuario en el curso
        $user->courses()->attach($courseId, ['progress' => 0]);

        // Actualizar dinámicamente el estado de is_registered
        foreach ($this->getCurrentCourses() as $course) {
            if ($course->id == $courseId) {
                $course->is_registered = true;
            }
        }

        session()->flash('message', 'Te has registrado correctamente en el curso.');
        $this->dispatch('registered',$courseId);
    }
}
