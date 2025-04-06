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
        $tglPinjam = now()->subDays(rand(1, 10));
        $estKembali = $tglPinjam->copy()->addDays(3);

        // Acak apakah telat atau tidak
        $tglKembali = (clone $estKembali)->addDays(rand(-2, 5));

        $hariTelat = $tglKembali->greaterThan($estKembali)
            ? abs($tglKembali->diffInDays($estKembali, false))
            : 0;

        $denda = $hariTelat * 500;

        return [
            'nisn' => User::inRandomOrder()->value('nisn') ?? User::factory()->create()->nisn,
            'no_regis' => Buku::inRandomOrder()->value('no_regis') ?? Buku::factory()->create()->no_regis,
            'fullname' => fake()->name(),
            'judul' => Buku::inRandomOrder()->value('judul') ?? Buku::factory()->create()->judul,
            'tgl_pinjam' => $tglPinjam,
            'est_kembali' => $estKembali,
            'tgl_kembali' => $tglKembali,
            'denda' => $denda,
            'pinjam' => fake()->randomElement(['terima', 'tolak', 'verifikasi']),
            'kembali' => fake()->randomElement(['selesai', 'verifikasi', '-', null]),
        ];
    }
}
