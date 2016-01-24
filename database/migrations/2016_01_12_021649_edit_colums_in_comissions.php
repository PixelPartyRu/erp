<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumsInComissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions', function($table)
        {
            $table->boolean('nds')->nullable()->default(false)->change();
            $table->boolean('deduction')->nullable()->default(false)->change();
            $table->boolean('payer')->nullable()->default(false)->change();
            $table->boolean('active')->nullable()->default(true)->change();
            $table->boolean('additional_sum')->nullable()->default(false)->change();
            $table->boolean('rate_stitching')->nullable()->default(false)->change();
            $table->boolean('time_of_settlement')->nullable()->default(false)->change();
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
