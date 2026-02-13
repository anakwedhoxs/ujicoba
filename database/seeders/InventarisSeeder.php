<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventaris;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        Inventaris::insert([
            [
                'Kategori'   => 'Baterai UPS',
                'Merk'       => 'ICAL',
                'Seri'       => 'IP1272',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Baterai UPS',
                'Merk'       => 'PROLINK',
                'Seri'       => 'FIDA 1274',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Printer',
                'Merk'       => 'EPSON',
                'Seri'       => 'L310',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Printer',
                'Merk'       => 'Epson',
                'Seri'       => 'L120',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Monitor',
                'Merk'       => 'LG',
                'Seri'       => 'W1643SV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
