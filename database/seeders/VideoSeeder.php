<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asocia videos a cursos existentes
        $courses = Course::all();

        foreach ($courses as $course) {
            Video::factory()->count(5)->create([
                'course_id' => $course->id,
                'category_id' => $course->category_id, // Usa la categoría del curso
            ]);
        }
    }
}
