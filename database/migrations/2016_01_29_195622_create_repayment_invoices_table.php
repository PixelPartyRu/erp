<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayment_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('delivery_id')->unsigned();
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');

            $table->float('sum');
            $table->string('type',50)->nullable();
            $table->bigInteger('repayment_id')->unsigned();
            $table->foreign('repayment_id')->references('id')->on('repayments')->onDelete('cascade');
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
        Schema::drop('repayment_invoices');
    }
}
