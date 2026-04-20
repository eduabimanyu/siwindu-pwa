<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('id_member')->nullable();
            $table->BigInteger('wisata');
            $table->BigInteger('total_item');
            $table->BigInteger('diskon');
            $table->BigInteger('bayar');
            $table->BigInteger('diterima');
            $table->string('jenis_pembayaran',30);
            $table->BigInteger('bank');
            $table->BigInteger('ewalet');
            $table->BigInteger('id_user');
            $table->string('status',30);
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
        Schema::dropIfExists('transaksis');
    }
}
