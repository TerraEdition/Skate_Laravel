<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu');
            $table->string('url');
            $table->string('icon');
            $table->string('slug');
            $table->string('parent_id');
            $table->string('tab_id');
            $table->timestamps();
        });

        DB::table('menus')->insert([
            [
                'menu' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fa-solid fa-house',
                'slug' => 'dashboard',
                'parent_id' => '0',
                'tab_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Tim & Peserta',
                'url' => '/team',
                'icon' => 'fa-solid fa-people-group',
                'slug' => 'tim',
                'parent_id' => '0',
                'tab_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Turnamen',
                'url' => '/tournament',
                'icon' => 'fa-solid fa-trophy',
                'slug' => 'turnamen',
                'parent_id' => '0',
                'tab_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Pertandingan',
                'url' => '/participant',
                'icon' => 'fa-solid fa-person-skating',
                'slug' => 'pertandingan',
                'parent_id' => '0',
                'tab_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Pengguna',
                'url' => '/user',
                'icon' => 'fa-solid fa-user',
                'slug' => 'user',
                'parent_id' => '0',
                'tab_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Ganti Password',
                'url' => '/password',
                'icon' => 'fa-solid fa-lock',
                'slug' => 'password',
                'parent_id' => '0',
                'tab_id' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
