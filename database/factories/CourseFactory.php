<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = Category::all();
        return [
            'name' => $this->faker->sentence(3), // Nombre del curso
            'description' => $this->faker->paragraph(), // Descripción
            'category_id' => $categories->count() > 0 ? $categories->random()->id : Category::factory(), // Relación con categoría
            'age_group' => $this->faker->randomElement(['5-8', '9-13', '14-16', '16+']), // Grupo de edades
        ];
    }
}
