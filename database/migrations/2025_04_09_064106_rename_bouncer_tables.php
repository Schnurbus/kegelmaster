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
            $table->dropForeign('players_role_id_foreign');
            $table->renameColumn('role_id', 'bouncer_role_id');
        });

        Schema::rename('abilities', 'bouncer_abilities');
        Schema::rename('roles', 'bouncer_roles');
        Schema::rename('assigned_roles', 'bouncer_assigned_roles');
        Schema::rename('permissions', 'bouncer_permissions');

        Schema::table('players', function (Blueprint $table) {
            $table->foreign('bouncer_role_id')->references('id')->on('bouncer_roles');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign('players_bouncer_role_id_foreign');
            $table->renameColumn('bouncer_role_id', 'role_id');
        });

        Schema::rename('bouncer_permissions', 'permissions');
        Schema::rename('bouncer_assigned_roles', 'assigned_roles');
        Schema::rename('bouncer_roles', 'roles');
        Schema::rename('bouncer_abilities', 'abilities');

        Schema::table('players', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }
};
