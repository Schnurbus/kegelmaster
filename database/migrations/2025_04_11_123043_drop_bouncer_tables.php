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
            $table->dropColumn('bouncer_role_id');
        });

        Schema::dropIfExists('bouncer_assigned_roles');
        Schema::dropIfExists('bouncer_permissions');
        Schema::dropIfExists('bouncer_roles');
        Schema::dropIfExists('bouncer_abilities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
