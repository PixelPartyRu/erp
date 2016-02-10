<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCharge extends Migration
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
            $table->renameColumn('udeferment_penalty_nds', 'deferment_penalty_nds');
        });

        Schema::table('charge_commission_views', function(Blueprint $table)
        {
            $table->renameColumn('udeferment_penalty_nds', 'deferment_penalty_nds');
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
