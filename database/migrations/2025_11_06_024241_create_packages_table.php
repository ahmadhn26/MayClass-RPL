<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('level');
            $table->string('tag')->nullable();
            $table->string('card_price_label');
            $table->string('detail_title');
            $table->string('detail_price_label');
            $table->string('image_url');
            $table->decimal('price', 12, 2);
            $table->text('summary');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
