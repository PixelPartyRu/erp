<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyChargeCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_charge_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->float('fixed_charge')->nullable();
            $table->float('percent')->nullable();
            $table->float('udz')->nullable();
            $table->float('deferment_penalty')->nullable();
            $table->float('without_nds')->nullable();
            $table->float('nds')->nullable();
            $table->float('with_nds')->nullable();

            $table->boolean('handler')->default(false)->nullable();

            $table->float('fixed_charge_nds')->nullable();
            $table->float('percent_nds')->nullable();
            $table->float('udz_nds')->nullable();
            $table->float('deferment_penalty_nds')->nullable();

            $table->integer('delivery_id')->unsigned();
            $table->foreign('delivery_id')->references('id')->on('deliveries');

            $table->integer('charge_commission_id')->unsigned();
            $table->foreign('charge_commission_id')->references('id')->on('charge_commissions');

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
        Schema::drop('daily_charge_commissions');
    }
}
