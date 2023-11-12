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
        Schema::create('participant_tournament_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('group_id');
            $table->string('time')->default('00:00:000');
            $table->integer('seat')->comment("Group Participant");
            $table->integer('round');
            $table->timestamps();
        });
        Schema::create('setting_group_rounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->integer('passes');
            $table->integer('round');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_tournament_detail');
        Schema::dropIfExists('setting_group_rounds');
    }
};
