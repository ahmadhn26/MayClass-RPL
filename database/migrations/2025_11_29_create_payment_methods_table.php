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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // 'transfer_bank', 'shopeepay', etc
            $table->string('name'); // 'Transfer Bank', 'ShopeePay'
            $table->enum('type', ['bank', 'ewallet'])->default('bank');
            $table->string('account_number')->nullable(); // Nomor rekening/HP
            $table->string('account_holder')->nullable(); // Atas nama
            $table->string('bank_name')->nullable(); // Nama bank (untuk type=bank)
            $table->text('instructions')->nullable(); // Instruksi khusus
            $table->string('icon')->nullable(); // Icon/logo path
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
