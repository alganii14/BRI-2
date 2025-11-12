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
            $table->foreignId('rencana_aktivitas_id')->nullable()->after('strategy_pipeline')->constrained('rencana_aktivitas')->onDelete('set null');
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
            $table->dropForeign(['rencana_aktivitas_id']);
            $table->dropColumn('rencana_aktivitas_id');
        });
    }
};
