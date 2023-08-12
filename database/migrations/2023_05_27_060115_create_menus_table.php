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
            $table->timestamps();
        });

        DB::table('menus')->insert([
            [
                'menu' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fa-solid fa-house',
                'slug' => 'dashboard',
                'parent_id' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Tim',
                'url' => '/team',
                'icon' => 'fa-brands fa-microsoft',
                'slug' => 'tim',
                'parent_id' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Turnamen',
                'url' => '/tournament',
                'icon' => 'fa-brands fa-microsoft',
                'slug' => 'turnamen',
                'parent_id' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'menu' => 'Peserta',
                'url' => '/participant',
                'icon' => 'fa-brands fa-microsoft',
                'slug' => 'peserta',
                'parent_id' => '0',
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
