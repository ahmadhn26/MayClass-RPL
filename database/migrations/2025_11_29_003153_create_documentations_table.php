<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->string('photo_path'); // Path foto kegiatan
            $table->date('activity_date'); // Tanggal kegiatan
            $table->text('description'); // Kesan/deskripsi kegiatan
            $table->integer('week_number'); // Nomor minggu dalam tahun (1-52)
            $table->integer('year'); // Tahun
            $table->boolean('is_active')->default(true); // Status aktif
            $table->integer('order')->default(0); // Urutan tampil (semakin besar = semakin baru)
            $table->timestamps();

            // Index untuk query cepat berdasarkan minggu
            $table->index(['year', 'week_number', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
