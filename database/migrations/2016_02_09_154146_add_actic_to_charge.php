<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActicToCharge extends Migration
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
            $table->boolean('avtive')->default(true)->nullable();
        });

        Schema::table('charge_commission_views', function(Blueprint $table)
        {
            $table->boolean('avtive')->default(true)->nullable();
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
