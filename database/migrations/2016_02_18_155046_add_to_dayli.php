<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToDayli extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_charge_commissions', function (Blueprint $table) {
            $table->bigInteger('repayment_id')->nullable()->unsigned();
            $table->foreign('repayment_id')->references('id')->on('repayments');

            $table->float('repayment_sum')->nullable();//
            $table->float('first_payment_sum')->nullable();
            $table->float('first_payment_debt_after')->nullable();
            $table->float('first_payment_debt_before')->nullable();//
            $table->float('balance_owed_before')->nullable();
            $table->float('to_client')->nullable();
            $table->string('type_of_payment',50)->nullable();
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
