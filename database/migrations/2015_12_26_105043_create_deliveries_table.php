<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('debtor_id')->unsigned();
            $table->foreign('debtor_id')->references('id')->on('debtors')->onDelete('cascade');

            $table->string('waybill',50);
            $table->float('waybill_amount');
            $table->float('first_payment_amount');
            $table->float('balance_owed');
            $table->float('remainder_of_the_debt_first_payment');
            $table->date('date_of_waybill');
            $table->date('due_date');
            $table->date('date_of_payment');
            $table->date('date_of_recourse');
            $table->date('the_date_of_termination_of_the_period_of_regression');
            $table->date('the_date_of_a_regular_supply');
            $table->integer('the_actual_delay');
            $table->string('invoice',50);
            $table->date('date_of_invoice');
            $table->string('registry',50);
            $table->date('date_of_registry');
            $table->date('date_of_funding');
            $table->date('end_date_of_funding');
            $table->string('notes',100);
            $table->boolean('return')->default(false);
            $table->boolean('state')->default(false);
            $table->boolean('status')->default(false);
            $table->float('balance_owed_rub');
            $table->boolean('state_debt')->default(false);
            $table->string('act',50);
            $table->date('date_of_act');
            $table->float('remainder_of_the_debt_first_payment_rub');
            $table->boolean('the_presence_of_the_original_document')->default(false);
            $table->integer('type_of_factoring');

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
        Schema::drop('deliveries');
    }
}
