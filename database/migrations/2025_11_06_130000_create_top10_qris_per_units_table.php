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
        Schema::create('top10_qris_per_units', function (Blueprint $table) {
            $table->id();
            $table->string('rank')->nullable();
            $table->string('posisi')->nullable();
            $table->string('mainbr')->nullable();
            $table->string('mbdesc')->nullable();
            $table->string('branch')->nullable();
            $table->string('brdesc')->nullable();
            $table->string('storeid')->nullable();
            $table->string('nama_merchant')->nullable();
            $table->string('no_rek')->nullable();
            $table->string('cif')->nullable();
            $table->string('pn')->nullable();
            $table->string('pn_pemrakasa')->nullable();
            $table->string('akumulasi_sv_total')->nullable();
            $table->string('posisi_sv_total_september')->nullable();
            $table->string('akumulasi_trx_total')->nullable();
            $table->string('posisi_trx_total')->nullable();
            $table->string('saldo_posisi')->nullable();
            $table->string('ratas_saldo')->nullable();
            $table->text('alamat')->nullable();
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
        Schema::dropIfExists('top10_qris_per_units');
    }
};
