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
        Schema::create('sows', function (Blueprint $table) {
        $table->id();

        $table->foreignId('inventaris_id')->constrained()->cascadeOnDelete();

        $table->date('tanggal_penggunaan')->nullable();
        $table->date('tanggal_perbaikan')->nullable();
        $table->boolean('helpdesk')->default(false);
        $table->boolean('form')->default(false);
        $table->string('nomor_perbaikan')->nullable();
        $table->foreignId('hostname_id')
                ->nullable()
                ->after('nomor_perbaikan') // ✅ posisi setelah nomor_perbaikan
                ->constrained('hostnames')
                ->onDelete('set null');
        $table->string('divisi')->nullable();
        $table->foreignId('pic_id')
              ->nullable()
              ->constrained('pics')
              ->onDelete('set null');
        $table->text('keterangan')->nullable();
        $table->string('foto')->nullable()->default('');
        $table->string('status')->nullable();
        

        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sows');
    }
};
