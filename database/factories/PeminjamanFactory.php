<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
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
            'no_regis' => Buku::inRandomOrder()->value('no_regis') ?? Buku::factory()->create()->no_regis,
            'fullname' => fake()->name(),
            'judul' => fake()->sentence(),
            'tgl_pinjam' => fake()->dateTime(),
            'tgl_kembali' => fake()->dateTime(),
            'denda' => fake()->randomNumber(2),
            'tahap' => fake()->randomElement(['pinjam', 'kembali']),
            'status' => fake()->randomElement(['terima', 'tolak', 'verifikasi']),
        ];
    }
}
