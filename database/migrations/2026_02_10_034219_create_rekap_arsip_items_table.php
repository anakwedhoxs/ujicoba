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
        Schema::create('rekap_arsip_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_arsip_id')->constrained('rekap_arsips')->cascadeOnDelete();
            $table->string('kategori')->nullable();
            $table->string('merk')->nullable();
            $table->string('seri')->nullable();
            $table->integer('jumlah')->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_arsip_items');
    }
};





