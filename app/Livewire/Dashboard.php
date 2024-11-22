<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $isAdmin;

    public function mount()
    {
        // Determinar si el usuario autenticado es administrador
        $this->isAdmin = auth()->user()->hasRole('admin');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
