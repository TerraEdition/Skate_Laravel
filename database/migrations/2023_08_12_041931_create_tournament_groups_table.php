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
        Schema::create('tournament_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->string('group')->nullable(false);
            $table->enum('gender', ['0', '1', '2'])->comment('0=>All, 1=>Male, 2=>Female');
            $table->text('description')->nullable(true);
            $table->integer('max_participant');
            $table->integer('max_per_team');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->enum('status', ['0', '1', '2'])->comment('0 => not yet start, 1 => already start, 2 => finished');
            $table->string('slug');
            $table->timestamps();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->index(['group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_groups');
    }
};
