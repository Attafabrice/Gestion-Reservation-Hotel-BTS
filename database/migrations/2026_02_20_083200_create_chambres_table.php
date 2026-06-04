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
    Schema::create('chambres', function (Blueprint $table) {
        $table->id();
        $table->string('numero')->unique();
        $table->integer('etage')->nullable();
        $table->Longtext('description')->nullable();
        $table->integer('capacite')->nullable(); 
        $table->integer('surface')->nullable();
        $table->string('image');
        $table->foreignId('type_chambre_id')->constrained('type_chambres')->cascadeOnDelete();
        $table->enum('statut', ['libre', 'occupee', 'maintenance'])->default('libre');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
