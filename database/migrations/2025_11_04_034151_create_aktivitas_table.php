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
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rmft_id')->constrained('rmfts')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_rmft');
            $table->string('pn');
            $table->string('kode_kc');
            $table->string('nama_kc');
            $table->string('kode_uker');
            $table->string('nama_uker');
            $table->string('kelompok');
            $table->string('rencana_aktivitas');
            $table->string('segmen_nasabah');
            $table->string('nama_nasabah');
            $table->string('norek');
            $table->string('rp_jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktivitas');
    }
};
