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
        Schema::create('fee_type_version_matchday', function (Blueprint $table) {
            $table->foreignId('fee_type_version_id')->constrained()->cascadeOnDelete();
            $table->foreignId('matchday_id')->constrained()->cascadeOnDelete();
            $table->primary(['fee_type_version_id', 'matchday_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_type_version_matchday');
    }
};
