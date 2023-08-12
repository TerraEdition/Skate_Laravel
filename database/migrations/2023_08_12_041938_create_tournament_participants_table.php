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
        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('group_id');
            $table->time('time');
            $table->timestamps();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('member_id')->references('id')->on('team_members');
            $table->foreign('group_id')->references('id')->on('tournament_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_participants');
    }
};
