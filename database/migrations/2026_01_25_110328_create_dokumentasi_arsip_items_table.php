<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumentasi_arsip_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumentasi_arsip_id')->constrained()->cascadeOnDelete();
            $table->string('nama_barang');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_arsip_items');
    }
};
