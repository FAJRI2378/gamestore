<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Makanan',
            'Minuman',
            'Fashion',
            'Elektronik',
            'Kesehatan',
            'Olahraga',
            'Rumah Tangga',
            'Mainan',
            'Aksesoris',
            'Lainnya'
        ];

        foreach ($kategoris as $nama) {
            Kategori::create(['name' => $nama]);
        }
    }
}
