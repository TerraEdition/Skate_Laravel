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
        Schema::create('team_members_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('team_id');
            $table->string('member')->nullable(false);
            $table->enum('gender', ['1', '2'])->comment('1=>Male, 2=>Female');
            $table->integer('birth')->comment("Year");
            $table->string('address')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('email')->nullable(true);
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
        Schema::dropIfExists('team_members_log');
    }
};
