<?php


namespace App\Imports;


use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class InventarisImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Inventaris([
            'Kategori' => $row['kategori'],
            'Merk'     => $row['merk'],
            'Seri'     => $row['seri'],
        ]);
    }
}





