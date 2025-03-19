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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->nullable()->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('player_id')->nullable()->constrained('players');
            $table->foreignId('matchday_id')->nullable()->constrained('matchdays');
            $table->foreignId('fee_entry_id')->nullable()->constrained('fee_entries');
            $table->integer('type');
            $table->integer('amount');
            $table->date('date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['player_id', 'type'])->include('amount');
            $table->index(['club_id', 'type'])->include('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
