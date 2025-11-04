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
        Schema::create('ukers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sub_kanca')->nullable();
            $table->string('sub_kanca')->nullable();
            $table->string('segment')->nullable();
            $table->string('kode_kanca')->nullable();
            $table->string('kanca')->nullable();
            $table->string('kanwil')->nullable();
            $table->string('kode_kanwil')->nullable();
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
        Schema::dropIfExists('ukers');
    }
};
