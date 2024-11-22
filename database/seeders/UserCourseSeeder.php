<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        $aleatory_courses = $courses->random(2)->pluck('id')->toArray();

        foreach ($users as $user) {
            $user->courses()->attach(
               $aleatory_courses,
                ['progress' => 0] // Progreso aleatorio
            );
        }

    }
}
