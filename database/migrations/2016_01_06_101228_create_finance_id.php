<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceId extends Migration
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
            $table->integer('finance_id')->nullable()->unsigned();
            $table->foreign('finance_id')->references('id')->on('finances')->onDelete('cascade'); 
        });

         Schema::table('finances', function(Blueprint $table)
        {
            $table->dropForeign('delivery_id');
            $table->dropColumn('delivery_id');
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
