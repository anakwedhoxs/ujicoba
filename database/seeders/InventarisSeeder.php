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
                'Kategori'   => 'Laptop',
                'Merk'       => 'Dell',
                'Seri'       => 'Latitude 5420',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Laptop',
                'Merk'       => 'HP',
                'Seri'       => 'ProBook 440 G8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Printer',
                'Merk'       => 'Canon',
                'Seri'       => 'PIXMA G2010',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Printer',
                'Merk'       => 'Epson',
                'Seri'       => 'L3110',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Kategori'   => 'Monitor',
                'Merk'       => 'Samsung',
                'Seri'       => 'S24F350',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
