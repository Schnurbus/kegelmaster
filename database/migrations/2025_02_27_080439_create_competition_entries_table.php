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
        Schema::create('competition_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matchday_id')->constrained('matchdays')->index();
            $table->foreignId('player_id')->constrained('players');
            $table->foreignId('competition_type_id')->constrained('competition_types')->cascadeOnDelete();
            $table->integer('amount');
            $table->timestamps();

            $table->index('matchday_id');
            $table->unique(['matchday_id', 'player_id', 'competition_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_entries');
    }
};
