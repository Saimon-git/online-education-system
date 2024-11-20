<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(), // Contenido del comentario
            'is_approved' => $this->faker->boolean(50), // Aprobado o no (50% de probabilidad)
            'user_id' => User::factory(), // Relación con usuario
            'video_id' => Video::factory(), // Relación con video
        ];
    }
}
