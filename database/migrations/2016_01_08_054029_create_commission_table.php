<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->boolean('nds')->default(false);
            $table->boolean('deduction')->default(false);
            $table->boolean('payer')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('additional_sum')->default(false);
            $table->boolean('rate_stitching')->default(false);
            $table->boolean('time_of_settlement')->default(false);
            $table->float('commission_value')->nullable(); 
            $table->integer('tariff_id')->unsigned();
            $table->foreign('tariff_id')->references('id')->on('tariffs');
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
         Schema::drop('commissions');
    }
}
