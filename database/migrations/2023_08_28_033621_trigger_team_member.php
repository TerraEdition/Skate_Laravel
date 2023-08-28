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
            CREATE TRIGGER team_members_insert_trigger
            AFTER INSERT ON team_members FOR EACH ROW
            BEGIN
                INSERT INTO teams_log (
                    member_id,
                    team_id,
                    member,
                    gender,
                    birth,
                    address,
                    email,
                    image,
                    slug,
                    created_at,
                    updated_at,
                    status_log
                ) VALUES (
                    NEW.id,
                    NEW.team_id,
                    NEW.member,
                    NEW.gender,
                    NEW.birth,
                    NEW.address,
                    NEW.image,
                    NEW.slug,
                    NEW.created_at,
                    NEW.updated_at,
                    "Insert"
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER team_members_update_trigger
            BEFORE UPDATE ON team_members FOR EACH ROW
            BEGIN
                INSERT INTO teams_log (
                   member_id,
                    team_id,
                    member,
                    gender,
                    birth,
                    address,
                    email,
                    image,
                    slug,
                    created_at,
                    updated_at,
                    status_log
                ) VALUES (
                    NEW.id,
                    NEW.team_id,
                    NEW.member,
                    NEW.gender,
                    NEW.birth,
                    NEW.address,
                    NEW.image,
                    NEW.slug,
                    NEW.created_at,
                    NEW.updated_at,
                    "Update"
                );
            END
        ');

        DB::unprepared('
            CREATE TRIGGER team_members_delete_trigger
            AFTER DELETE ON team_members FOR EACH ROW
            BEGIN
                INSERT INTO teams_log (
                    member_id,
                    team_id,
                    member,
                    gender,
                    birth,
                    address,
                    email,
                    image,
                    slug,
                    created_at,
                    updated_at,
                    status_log
                ) VALUES (
                    OLD.id,
                    OLD.team_id,
                    OLD.member,
                    OLD.gender,
                    OLD.birth,
                    OLD.address,
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