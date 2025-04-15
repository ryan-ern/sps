<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class KontenDigitalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        return [
            'nuptk' => $user->nisn,
            'jenis' => fake()->randomElement(['buku digital', 'video']),
            'judul' => fake()->sentence(),
            'url' => fake()->url(),
            'cover' => 'default/default-book.png',
            'file_path' => 'default/default-book.png',
            'pembuat' => $user->fullname,
            'dilihat' => fake()->randomNumber(2),
        ];
    }
}
