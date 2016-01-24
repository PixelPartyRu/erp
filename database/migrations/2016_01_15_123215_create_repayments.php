<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('number');
            $table->date('date')->nullable();
            $table->integer('payer_id');
            $table->bigInteger('payer_inn');
            $table->integer('payer_type');
            $table->string('info',100)->nullable();
            $table->float('sum');
            $table->float('balance');

            $table->integer('recepient_id')->nullable();
            $table->integer('recepient_type')->nullable();

            $table->string('purpose_of_payment',300)->nullable();
            
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
        Schema::drop('repayments');
    }
}
