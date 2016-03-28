<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInBillAgreement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::table('bills', function(Blueprint $table)
        {   
            $table->dropColumn('agreement'); 
            $table->integer('agreement_id')->unsigned();
            $table->foreign('agreement_id')->references('id')->on('agreements');
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
