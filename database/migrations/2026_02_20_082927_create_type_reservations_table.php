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
        Schema::create('type_reservations', function (Blueprint $table) {
            $table->id();
            $table->enum('libelle', ['passage', 'nuitée', 'sejour'])->default('passage'); //Passage nuitée séjour
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_reservations');
    }
};
