<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWaitingPeriodTypeSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relations', function($table)
        {
            $table->string('waiting_period_type', 50)->change();
            $table->string('regress_period_type', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relations', function($table)
        {
            $table->string('waiting_period_type', 10)->change();
            $table->string('regress_period_type', 10)->change();
        });
    }
}
