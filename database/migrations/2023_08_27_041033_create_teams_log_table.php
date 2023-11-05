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
        Schema::create('teams_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('team')->nullable(false);
            $table->string('team_initial')->nullable(false);
            $table->string('coach');
            $table->string('website');
            $table->string('address');
            $table->string('email');
            $table->string('image')->default('default.png');
            $table->string('slug');
            $table->string('status_log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams_log');
    }
};
