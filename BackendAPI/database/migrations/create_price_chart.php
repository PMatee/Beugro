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
        Schema::create('price_chart', function (Blueprint $table) {
            $table->id();
            $table->dateTime('timestamp')->unique();
            $table->decimal('price_eur_mwh', 10, 6);
            $table->decimal('price_huf_kwh', 10, 2);
        });

      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_chart');
       
    }
};
