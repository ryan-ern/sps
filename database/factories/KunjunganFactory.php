<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kunjungan>
 */
class KunjunganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nisn' => User::inRandomOrder()->value('nisn') ?? User::factory()->create()->nisn,
            'fullname' => fake()->name(),
            'kelas' => fake()->randomElement(['7A', '7B', '8A', '8B', '9A', '9B']),
            'keterangan' => fake()->sentence(),
        ];
    }
}
