<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 's.montoya@mail.test', // Password en claro
        ])->assignRole('admin');

        User::factory(10)
            ->hasAttached(
                Course::factory()->count(2), // Relación con 2 cursos
                ['progress' => 50] // Valores adicionales en la tabla pivote
            )
            ->create()->each(function ($user) {
                $user->assignRole('user');
            });

        $users = User::all();

        foreach ($users as $user) {
            // Genera un token automáticamente
            $token = $user->createToken('default-token');
        }

    }
}
