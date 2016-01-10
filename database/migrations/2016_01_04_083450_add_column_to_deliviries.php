<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToDeliviries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function(Blueprint $table)
        {
            $table->integer('due_date');
            $table->date('date_of_regress');
            $table->string('the_date_of_a_registaration_supply');
            $table->string('the_actual_deferment');
            $table->string('return');
            $table->string('status');
            $table->boolean('type_of_factoring')->default(false);;   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function(Blueprint $table)
        {
            $table->dropColumn(array('due_date', 'the_date_of_a_regular_supply', 'the_actual_delay','return','status','balance_owed_rub','state_debt','act','date_of_act','remainder_of_the_debt_first_payment_rub','type of factoring'));
        });
    }
}
