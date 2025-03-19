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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('club_id')->constrained('clubs')->index();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('role_id')->constrained('roles');
            $table->integer('sex')->default(0);
            $table->integer('balance')->default(0);
            $table->integer('initial_balance')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['name', 'club_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
