<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea algunas categorías
        $categories = Category::all();
        //dd($categories);

        // Crea cursos asignándolos a categorías aleatorias
        Course::factory()->count(10)->create()->each(function ($course) use ($categories) {
            $course->category_id = $categories->random()->id;
            $course->save();
        });
    }
}
