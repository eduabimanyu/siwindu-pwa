<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wisata');
            $table->foreign('wisata')->references('id')->on('wisatas')->onDetele('cascade');
            $table->unsignedBigInteger('kasir');
            $table->foreign('kasir')->references('id')->on('users')->onDetele('cascade');
            $table->string('start',30);
            $table->string('finish',30);
            $table->unsignedBigInteger('total_pendapatan');
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
        Schema::dropIfExists('shifts');
    }
}
