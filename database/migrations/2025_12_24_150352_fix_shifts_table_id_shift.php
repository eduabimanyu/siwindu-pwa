<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixShiftsTableIdShift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Simply modify the id_shift column to ensure it's AUTO_INCREMENT
        // This will work whether the column is named 'id' or 'id_shift'
        DB::statement('ALTER TABLE shifts MODIFY COLUMN id_shift BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to reverse - AUTO_INCREMENT should remain
    }
}
