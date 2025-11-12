<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if indexes already exist before creating them
        $indexes = $this->getIndexes('nasabahs');
        
        Schema::table('nasabahs', function (Blueprint $table) use ($indexes) {
            // Index untuk filter by KC (paling sering digunakan dan paling selektif)
            if (!in_array('idx_nasabahs_kode_kc', $indexes)) {
                $table->index('kode_kc', 'idx_nasabahs_kode_kc');
            }
            
            // Index untuk filter by Unit/Uker
            if (!in_array('idx_nasabahs_kode_uker', $indexes)) {
                $table->index('kode_uker', 'idx_nasabahs_kode_uker');
            }
            
            // Index untuk search by CIFNO (exact start match dengan LIKE 'xxx%')
            if (!in_array('idx_nasabahs_cifno', $indexes)) {
                $table->index('cifno', 'idx_nasabahs_cifno');
            }
            
            // Composite index untuk query yang filter KC dan Unit sekaligus
            // Ini akan mempercepat query: WHERE kode_kc = ? AND kode_uker IN (?,?,?)
            if (!in_array('idx_nasabahs_kc_uker', $indexes)) {
                $table->index(['kode_kc', 'kode_uker'], 'idx_nasabahs_kc_uker');
            }
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
            $indexes = $this->getIndexes('nasabahs');
            
            if (in_array('idx_nasabahs_kc_uker', $indexes)) {
                $table->dropIndex('idx_nasabahs_kc_uker');
            }
            if (in_array('idx_nasabahs_cifno', $indexes)) {
                $table->dropIndex('idx_nasabahs_cifno');
            }
            if (in_array('idx_nasabahs_kode_uker', $indexes)) {
                $table->dropIndex('idx_nasabahs_kode_uker');
            }
            if (in_array('idx_nasabahs_kode_kc', $indexes)) {
                $table->dropIndex('idx_nasabahs_kode_kc');
            }
        });
    }
    
    /**
     * Get all indexes for a table
     */
    private function getIndexes($tableName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        return collect($indexes)->pluck('Key_name')->unique()->toArray();
    }
};
