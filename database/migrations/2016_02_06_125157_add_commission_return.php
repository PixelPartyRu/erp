<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charge_commissions', function(Blueprint $table)
        {
            $table->boolean('fixed_charge_return')->default(false);
            $table->boolean('percent_return')->default(false);
            $table->boolean('udz_return')->default(false);
            $table->boolean('deferment_penalty_return')->default(false);
        });

        Schema::table('charge_commission_views', function(Blueprint $table)
        {
            $table->boolean('fixed_charge_return')->default(false);
            $table->boolean('percent_return')->default(false);
            $table->boolean('udz_return')->default(false);
            $table->boolean('deferment_penalty_return')->default(false);
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
