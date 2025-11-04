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
        Schema::create('rmfts', function (Blueprint $table) {
            $table->id();
            $table->string('pernr')->nullable();
            $table->string('completename')->nullable();
            $table->string('jg')->nullable();
            $table->string('esgdesc')->nullable();
            $table->string('kanca')->nullable();
            $table->foreignId('uker_id')->nullable()->constrained('ukers')->onDelete('set null');
            $table->string('uker')->nullable();
            $table->string('uker_tujuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('kelompok_jabatan')->nullable();
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
        Schema::dropIfExists('rmfts');
    }
};
