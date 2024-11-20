<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4), // Título del video
            'url' => $this->faker->url(), // URL del video (puedes ajustar para URLs de YouTube si quieres)
            'duration' => $this->faker->randomElement(['2m 10s', '3m 45s', '5m 30s']),
            'likes' => $this->faker->numberBetween(0, 1000),
            'views' => $this->faker->numberBetween(0, 10000),
            'category_id' => Category::factory(), // Relación con categoría
            'course_id' => Course::factory(), // Relación con curso
        ];
    }
}
