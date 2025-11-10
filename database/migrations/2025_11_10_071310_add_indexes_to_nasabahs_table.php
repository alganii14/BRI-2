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
            // Index untuk filter by KC (paling sering digunakan dan paling selektif)
            $table->index('kode_kc', 'idx_nasabahs_kode_kc');
            
            // Index untuk filter by Unit/Uker
            $table->index('kode_uker', 'idx_nasabahs_kode_uker');
            
            // Index untuk search by CIFNO (exact start match dengan LIKE 'xxx%')
            $table->index('cifno', 'idx_nasabahs_cifno');
            
            // Composite index untuk query yang filter KC dan Unit sekaligus
            // Ini akan mempercepat query: WHERE kode_kc = ? AND kode_uker IN (?,?,?)
            $table->index(['kode_kc', 'kode_uker'], 'idx_nasabahs_kc_uker');
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
            // Drop indexes in reverse order
            $table->dropIndex('idx_nasabahs_kc_uker');
            $table->dropIndex('idx_nasabahs_cifno');
            $table->dropIndex('idx_nasabahs_kode_uker');
            $table->dropIndex('idx_nasabahs_kode_kc');
        });
    }
};
