<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFloatToDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_charge_commissions', function($table)
        {
            $table->decimal('fixed_charge', 15, 2)->nullable()->change();
            $table->decimal('percent', 15, 2)->nullable()->change();
            $table->decimal('udz', 15, 2)->nullable()->change();
            $table->decimal('deferment_penalty', 15, 2)->nullable()->change();
            $table->decimal('without_nds', 15, 2)->nullable()->change();
            $table->decimal('nds', 15, 2)->nullable()->change();
            $table->decimal('with_nds', 15, 2)->nullable()->change();
            $table->decimal('fixed_charge_nds', 15, 2)->nullable()->change();
            $table->decimal('percent_nds', 15, 2)->nullable()->change();
            $table->decimal('udz_nds', 15, 2)->nullable()->change();
            $table->decimal('deferment_penalty_nds', 15, 2)->nullable()->change();
            $table->decimal('repayment_sum', 15, 2)->nullable()->change();//
            $table->decimal('first_payment_sum', 15, 2)->nullable()->change();
            $table->decimal('first_payment_debt_after', 15, 2)->nullable()->change();
            $table->decimal('first_payment_debt_before', 15, 2)->nullable()->change();//
            $table->decimal('balance_owed_after', 15, 2)->nullable()->change();
            $table->decimal('to_client', 15, 2)->nullable()->change();
        });

        Schema::table('charge_commissions', function($table)
        {
            $table->decimal('fixed_charge', 15, 2)->nullable()->change();
            $table->decimal('percent', 15, 2)->nullable()->change();
            $table->decimal('udz', 15, 2)->nullable()->change();
            $table->decimal('deferment_penalty', 15, 2)->nullable()->change();
            $table->decimal('without_nds', 15, 2)->nullable()->change();
            $table->decimal('nds', 15, 2)->nullable()->change();
            $table->decimal('with_nds', 15, 2)->nullable()->change();
            $table->decimal('debt', 15, 2)->nullable()->change();
            $table->decimal('fixed_charge_nds', 15, 2)->nullable()->change();
            $table->decimal('percent_nds', 15, 2)->nullable()->change();
            $table->decimal('udz_nds', 15, 2)->nullable()->change();
            $table->decimal('deferment_penalty_nds', 15, 2)->nullable()->change();
        });

         Schema::table('deliveries', function($table)
        {
            $table->decimal('waybill_amount', 15, 2)->change();
            $table->decimal('first_payment_amount', 15, 2)->change();
            $table->decimal('balance_owed', 15, 2)->nullable()->change();
            $table->decimal('remainder_of_the_debt_first_payment', 15, 2)->nullable()->change();
            $table->decimal('second_pay', 15, 2)->default(0)->nullable()->change();
        });

        Schema::table('finances', function($table)
        {
             $table->decimal('sum', 15, 2)->change();
        });

        // Schema::table('limits', function($table)
        // {
        //     $table->decimal('value', 15, 2)->change();
        // });
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
