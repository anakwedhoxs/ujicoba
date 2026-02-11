<?php


namespace App\Observers;


use App\Models\Sow;
use App\Models\Rekap;
use Illuminate\Support\Facades\DB;


class SowObserver
{
    /**
     * Handle the Sow "created" event.
     */
    public function created(Sow $sow): void
    {
        $this->updateRekap($sow);
    }


    /**
     * Handle the Sow "updated" event.
     */
    public function updated(Sow $sow): void
    {
        $this->updateRekap($sow);
    }


    /**
     * Handle the Sow "deleted" event.
     */
    public function deleted(Sow $sow): void
    {
        $this->updateRekap($sow);
    }


    /**
     * Handle the Sow "restored" event.
     */
    public function restored(Sow $sow): void
    {
        $this->updateRekap($sow);
    }


    /**
     * Handle the Sow "force deleted" event.
     */
    public function forceDeleted(Sow $sow): void
    {
        $this->updateRekap($sow);
    }


    /**
     * Update atau create rekap berdasarkan data Sow
     */
    private function updateRekap(Sow $sow): void
    {
        if (!$sow->inventaris) {
            return;
        }


        $inventaris = $sow->inventaris;
        $kategori = $inventaris->Kategori;
        $merk = $inventaris->Merk;
        $seri = $inventaris->Seri;


        // Hitung jumlah total SOW dengan kombinasi kategori/merk/seri yg sama
        $jumlah = DB::table('sows')
            ->join('inventaris', 'sows.inventaris_id', '=', 'inventaris.id')
            ->where('inventaris.Kategori', $kategori)
            ->where('inventaris.Merk', $merk)
            ->where('inventaris.Seri', $seri)
            ->count();


        // Update atau create rekap
        Rekap::updateOrCreate(
            [
                'kategori' => $kategori,
                'merk' => $merk,
                'seri' => $seri,
            ],
            [
                'jumlah' => $jumlah,
            ]
        );
    }
}




