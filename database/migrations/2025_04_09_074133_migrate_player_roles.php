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
        Schema::table('players', function (Blueprint $table) {
            DB::statement('
                    UPDATE players
                    SET role_id = bouncer_role_id
                    WHERE bouncer_role_id IS NOT NULL;
                ');

            $table->foreignId('role_id')->nullable(false)->change();
            $table->foreignId('bouncer_role_id')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
