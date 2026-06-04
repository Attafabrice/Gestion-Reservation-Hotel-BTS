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
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_chambre_id')->constrained('type_chambres')->onDelete('cascade');
            $table->foreignId('type_reservation_id')->constrained('type_reservations')->onDelete('cascade');
            $table->integer('prix');
            $table->unique(['type_chambre_id', 'type_reservation_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
