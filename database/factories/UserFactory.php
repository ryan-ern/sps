<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nisn' => fake()->unique()->randomNumber(6),
            'fullname' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'kelas' => fake()->randomNumber(2),
            'password' => bcrypt('123'),
            'role' => fake()->randomElement(['admin', 'guru', 'siswa']),
            'status' => fake()->randomElement(['aktif', 'tidak aktif']),
        ];
    }
}
