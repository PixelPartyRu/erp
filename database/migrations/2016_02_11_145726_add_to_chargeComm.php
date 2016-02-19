<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToChargeComm extends Migration
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
            $table->date('charge_date')->nullable();
        });

        Schema::table('charge_commission_views', function(Blueprint $table)
        {
            $table->date('charge_date')->nullable();
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
