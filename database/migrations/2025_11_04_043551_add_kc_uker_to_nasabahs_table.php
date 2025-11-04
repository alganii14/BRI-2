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
        Schema::table('nasabahs', function (Blueprint $table) {
            $table->string('kode_kc', 20)->nullable()->after('norek');
            $table->string('nama_kc', 255)->nullable()->after('kode_kc');
            $table->string('kode_uker', 20)->nullable()->after('nama_kc');
            $table->string('nama_uker', 255)->nullable()->after('kode_uker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nasabahs', function (Blueprint $table) {
            $table->dropColumn(['kode_kc', 'nama_kc', 'kode_uker', 'nama_uker']);
        });
    }
};
