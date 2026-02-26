<?php


namespace App\Traits;


use App\Models\Rekap;
use Illuminate\Support\Facades\DB;


trait UpdatesRekap
{
    // Hitung dan update rekap
    public function updateRekap(string $kategori, string $merk, string $seri): void
    {
        // Hitung jumlah dari SOW biasa
        $jumlahSow = DB::table('sows')
            ->join('inventaris', 'sows.inventaris_id', '=', 'inventaris.id')
            ->where('inventaris.Kategori', $kategori)
            ->where('inventaris.Merk', $merk)
            ->where('inventaris.Seri', $seri)
            ->count();


        // Hitung jumlah dari SowPC case saja
        $jumlahPC = DB::table('sow_pcs')
            ->join('inventaris', 'sow_pcs.case_id', '=', 'inventaris.id')
            ->where('inventaris.Kategori', 'PC CASE')
            ->where('inventaris.Merk', $merk)
            ->where('inventaris.Seri', $seri)
            ->count();


        $total = $jumlahSow + $jumlahPC;


        // Update atau create rekap
        Rekap::updateOrCreate(
            [
                'kategori' => $kategori,
                'merk'     => $merk,
                'seri'     => $seri,
            ],
            [
                'jumlah' => $total,
            ]
        );
    }


    // Trigger rekap otomatis
    public function triggerRekap(): void
    {
        if (isset($this->inventaris)) {
            // SOW biasa
            $this->updateRekap($this->inventaris->Kategori, $this->inventaris->Merk, $this->inventaris->Seri);
        } elseif (isset($this->case)) {
            // SowPC case
            $this->updateRekap('PC Case', $this->case->Merk, $this->case->Seri);
        }
    }
}



