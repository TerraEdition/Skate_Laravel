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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('member')->nullable(false);
            $table->enum('gender', ['1', '2'])->comment('1=>Male, 2=>Female');
            $table->date('birth');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('image')->default('default.png');
            $table->string('slug');
            $table->timestamps();
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};