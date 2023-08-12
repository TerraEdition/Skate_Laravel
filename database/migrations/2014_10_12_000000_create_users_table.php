<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('roles_id');
            $table->string('slug')->unique();
            $table->enum('is_active', ['0', '1'])->comment('0=>disabled, 1=>enabled');
            $table->enum('is_root', ['0', '1'])->comment('0=>false, 1=>true');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('roles_id')->references('id')->on('roles');
        });
        DB::table('users')->insert([
            'email' => 'admin@mail.com',
            'name' => 'Super Admin',
            'password' => Hash::make('1122'),
            'is_active' => '1',
            'roles_id' => '1',
            'is_root' => '1',
            'slug' => 'super-admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
