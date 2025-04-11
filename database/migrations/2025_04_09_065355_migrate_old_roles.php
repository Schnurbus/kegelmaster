<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('bouncer_roles') || ! Schema::hasTable('roles')) {
            Log::warning('Migration aborted: One or both tables (bouncer_roles, roles) do not exist.');

            return;
        }

        DB::table('bouncer_roles')->orderBy('id')->chunk(100, function ($bouncerRoles) {
            foreach ($bouncerRoles as $bouncerRole) {
                try {
                    DB::table('roles')->insert([
                        'club_id' => $bouncerRole->scope,
                        'name' => $bouncerRole->name,
                        'guard_name' => 'web',
                        'is_base_fee_active' => $bouncerRole->is_base_fee_active,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info("Migrated role: {$bouncerRole->name}");
                } catch (\Exception $e) {
                    Log::error("Error migrating role {$bouncerRole->name}: {$e->getMessage()}");
                    throw $e; // Oder eine andere Fehlerbehandlung
                }
            }
        });

        Log::info('Bouncer roles migration completed successfully.');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->truncate();
    }
};
