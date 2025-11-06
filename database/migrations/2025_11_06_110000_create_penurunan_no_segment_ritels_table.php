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
        Schema::create('penurunan_no_segment_ritels', function (Blueprint $table) {
            $table->id();
            $table->string('regional_office')->nullable();
            $table->string('kode_cabang_induk')->nullable();
            $table->string('cabang_induk')->nullable();
            $table->string('kode_uker')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('cifno')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('penurunan')->nullable();
            $table->string('product_type')->nullable();
            $table->string('nama_nasabah')->nullable();
            $table->string('segmentasi_bpr')->nullable();
            $table->string('jenis_simpanan')->nullable();
            $table->string('saldo_last_eom')->nullable();
            $table->string('saldo_terupdate')->nullable();
            $table->string('delta')->nullable();
            $table->string('pn_slot_1')->nullable();
            $table->string('pn_slot_2')->nullable();
            $table->string('pn_slot_3')->nullable();
            $table->string('pn_slot_4')->nullable();
            $table->string('pn_slot_5')->nullable();
            $table->string('pn_slot_6')->nullable();
            $table->string('pn_slot_7')->nullable();
            $table->string('pn_slot_8')->nullable();
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
        Schema::dropIfExists('penurunan_no_segment_ritels');
    }
};
