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
            $table->dropUnique(['norek']);
            $table->index('norek'); // Add regular index instead
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
            $table->dropIndex(['norek']);
            $table->unique('norek');
        });
    }
};
