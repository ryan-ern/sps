<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_regis' => fake()->unique()->randomNumber(6),
            'judul' => fake()->sentence(),
            'pengarang' => fake()->name(),
            'penerbit' => fake()->company(),
            'tahun' => fake()->year(),
            'stok' => 1,
            'keterangan' => fake()->sentence(),
            'file_buku' => 'default/default-book.png',
            'file_cover' => 'default/default-book.png',
            'jenis' => fake()->randomElement(['referensi', 'paket']),
            'status' => fake()->randomElement(['tersedia', 'tidak tersedia']),
        ];
    }
}
