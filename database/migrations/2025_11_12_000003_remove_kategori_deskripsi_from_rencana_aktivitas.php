<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rencana_aktivitas', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'deskripsi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_aktivitas', function (Blueprint $table) {
            $table->string('kategori')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }
};
