<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER teams_insert_trigger
            AFTER INSERT ON teams FOR EACH ROW
            BEGIN
                INSERT INTO teams_log (
                    team_id,
                    team,
                    team_initial,
                    coach,
                    website,
                    address,
                    email,
                    image,
                    slug,
                    created_at,
                    updated_at,
                    status_log
                ) VALUES (
                    NEW.id,
                    NEW.team,
                    NEW.team_initial,
                    NEW.coach,
                    NEW.website,
                    NEW.address,
                    NEW.email,
                    NEW.image,
                    NEW.slug,
                    NEW.created_at,
                    NEW.updated_at,
                    "Insert"
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER teams_update_trigger
            BEFORE UPDATE ON teams FOR EACH ROW
            BEGIN
                INSERT INTO teams_log (
                    team_id,
                    team,
                    team_initial,
                    coach,
                    website,
                    address,
                    email,
                    image,
                    slug,
                    created_at,
                    updated_at,
                    status_log
                ) VALUES (
                    NEW.id,
                    NEW.team,
                    NEW.team_initial,
                    NEW.coach,
                    NEW.website,
                    NEW.address,
                    NEW.email,
                    NEW.image,
                    NEW.slug,
                    NEW.created_at,
                    NEW.updated_at,
                    "Update"
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER teams_delete_trigger
            AFTER DELETE ON teams FOR EACH ROW
            BEGIN
                INSERT INTO teams_log (
                    team_id,
                    team,
                    team_initial,
                    coach,
                    website,
                    address,
                    email,
                    image,
                    slug,
                    created_at,
                    updated_at,
                    status_log
                ) VALUES (
                    OLD.id,
                    OLD.team,
                    OLD.team_initial,
                    OLD.coach,
                    OLD.website,
                    OLD.address,
                    OLD.email,
                    OLD.image,
                    OLD.slug,
                    OLD.created_at,
                    OLD.updated_at,
                    "Delete"
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS teams_insert_trigger;');
        DB::unprepared('DROP TRIGGER IF EXISTS teams_update_trigger;');
        DB::unprepared('DROP TRIGGER IF EXISTS teams_delete_trigger;');
    }
};
