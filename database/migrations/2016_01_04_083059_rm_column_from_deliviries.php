<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RmColumnFromDeliviries extends Migration
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
            $table->dropColumn(array('due_date', 'the_date_of_a_regular_supply', 'the_actual_delay','return','status','balance_owed_rub','state_debt','act','date_of_act','remainder_of_the_debt_first_payment_rub','type of factoring'));
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
