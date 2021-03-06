<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepaymentsAgainTwo extends Migration
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
            $table->date('date')->nullable();
            $table->string('info',100)->nullable();
            $table->bigInteger('inn');
            $table->float('sum');
            $table->float('balance');
            $table->string('purpose_of_payment',300)->nullable();
            $table->bigInteger('recepientInn')->nullable();
            $table->integer('type');

            $table->bigInteger('client_id')->nullable()->unsigned();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->bigInteger('debtor_id')->nullable()->unsigned();
            $table->foreign('debtor_id')->references('id')->on('debtors');
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
        //
    }
}
