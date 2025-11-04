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
            $table->foreignId('assigned_by')->nullable()->after('rmft_id')->constrained('users')->onDelete('set null');
            $table->enum('tipe', ['self', 'assigned'])->default('self')->after('assigned_by');
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
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['assigned_by', 'tipe']);
        });
    }
};
