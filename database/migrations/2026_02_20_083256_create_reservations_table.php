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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('chambre_id')->constrained('chambres')->onDelete('cascade');
            $table->foreignId('type_reservation_id')->constrained('type_reservations')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_jours')->default(1);
            $table->integer('prix_total');
            $table->enum('statut', ['en_attente', 'confirmee', 'annulee', 'terminee'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
