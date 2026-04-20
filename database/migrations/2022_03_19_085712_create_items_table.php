<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wisata');
            $table->foreign('wisata')->references('id')->on('wisatas')->onDetele('cascade');
            $table->unsignedBigInteger('kategori');
            $table->foreign('kategori')->references('id')->on('kategoris')->onDetele('cascade');
            $table->string('nama_item',30);
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
        Schema::dropIfExists('items');
    }
}
