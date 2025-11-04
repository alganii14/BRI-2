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
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->enum('status_realisasi', ['belum', 'tercapai', 'tidak_tercapai', 'lebih'])->default('belum')->after('keterangan');
            $table->string('nominal_realisasi')->nullable()->after('status_realisasi');
            $table->text('keterangan_realisasi')->nullable()->after('nominal_realisasi');
            $table->timestamp('tanggal_feedback')->nullable()->after('keterangan_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aktivitas', function (Blueprint $table) {
            $table->dropColumn(['status_realisasi', 'nominal_realisasi', 'keterangan_realisasi', 'tanggal_feedback']);
        });
    }
};
