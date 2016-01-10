<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableCommissionsRanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('commissions_rages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->float('value')->nullable();
            $table->integer('commission_id')->unsigned();
            $table->foreign('commission_id')->references('id')->on('commissions');
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
        Schema::drop('commissions_rages');
    }
}
