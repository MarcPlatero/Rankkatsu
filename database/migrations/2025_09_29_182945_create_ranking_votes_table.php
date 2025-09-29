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
        Schema::create('ranking_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_id')->constrained()->onDelete('cascade');
            $table->foreignId('ranking_option_id')->constrained('ranking_options')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // qui vota
            $table->timestamps();

            $table->unique(['ranking_id', 'user_id']); 
            // evita que un usuari voti més d’un cop al mateix rànquing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_votes');
    }
};
