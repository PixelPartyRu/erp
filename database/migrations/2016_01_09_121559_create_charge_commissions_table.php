<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargeCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client');
            $table->integer('debtor');
            $table->integer('registry');
            $table->string('waybill',50);
            $table->date('date_of_waybill');
            $table->float('fixed_charge')->nullable();
            $table->float('percent')->nullable();
            $table->float('udz')->nullable();
            $table->float('info_commission')->nullable();
            $table->float('deferment_penalty')->nullable();
            $table->float('without_nds')->nullable();
            $table->float('nds')->nullable();
            $table->float('with_nds')->nullable();
            $table->float('debt')->nullable();
            $table->date('date_of_repayment')->nullable();
            $table->date('date_of_funding')->nullable();
            $table->string('waybill_status')->nullable();

            $table->integer('delivery_id')->unsigned();
            $table->foreign('delivery_id')->references('id')->on('deliveries');

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
        Schema::drop('charge_commissions');
    }
}
