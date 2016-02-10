<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNds extends Migration
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
            $table->float('fixed_charge_nds')->nullable();
            $table->float('percent_nds')->nullable();
            $table->float('udz_nds')->nullable();
            $table->float('udeferment_penalty_nds')->nullable();
        });

        Schema::table('charge_commission_views', function(Blueprint $table)
        {
            $table->float('fixed_charge_nds')->nullable();
            $table->float('percent_nds')->nullable();
            $table->float('udz_nds')->nullable();
            $table->float('udeferment_penalty_nds')->nullable();
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
