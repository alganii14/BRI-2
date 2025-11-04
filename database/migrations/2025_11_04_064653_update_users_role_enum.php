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
        // Alter ENUM to add 'admin'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('manager', 'rmft', 'admin') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to original ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('manager', 'rmft') DEFAULT NULL");
    }
};
