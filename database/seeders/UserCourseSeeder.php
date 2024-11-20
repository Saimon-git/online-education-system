<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $courses = Course::all();

        // Registra a cada usuario en 2 cursos aleatorios
        foreach ($users as $user) {
            $user->courses()->attach(
                $courses->random(2)->pluck('id')->toArray(),
                ['progress' => rand(0, 100)] // Progreso aleatorio
            );
        }
    }
}
