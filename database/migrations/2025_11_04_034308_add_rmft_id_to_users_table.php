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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rmft_id')->nullable()->after('role')->constrained('rmfts')->onDelete('set null');
            $table->string('pernr')->nullable()->after('rmft_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rmft_id']);
            $table->dropColumn(['rmft_id', 'pernr']);
        });
    }
};
