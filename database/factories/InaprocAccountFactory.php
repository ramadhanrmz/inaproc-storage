<?php

namespace Database\Factories;

use App\Models\InaprocAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InaprocAccount>
 */
class InaprocAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'opd' => fake()->randomElement(['Biro Pengadaan Barang dan Jasa', 'Dinas Kesehatan', 'Dinas Pendidikan', 'RSUD Provinsi NTB']),
            'status' => fake()->randomElement(['PPK', 'PP', 'Bendahara', 'POKJA', 'PA', 'KPA']),
            'no_surat_permohonan' => fake()->bothify('###/###/DIKES/I/2026'),
            'perihal_permohonan' => fake()->randomElement(['Penerbitan Akun', 'Update Akun']),
            'no_sk' => fake()->bothify('821.29/###/UMUM/I/2026'),
            'user_id' => strtoupper(fake()->lexify('??????_NTB')),
            'nik' => fake()->numerify('################'),
            'nip' => fake()->numerify('##################'),
            'pangkat_gol' => fake()->randomElement(['Pembina, IV/a', 'Penata, III/c', 'Pengatur, II/c']),
            'jabatan' => fake()->jobTitle(),
            'no_hp' => fake()->numerify('081#########'),
            'alamat' => fake()->address(),
            'sumber' => fake()->randomElement(['Fisik', 'Digital']),
            'jenis_data' => fake()->randomElement(['Katalog v.6', 'SPSE']),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
