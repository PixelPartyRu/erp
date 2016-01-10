<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMinMaxToDefaultInRagesCommissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions_rages', function(Blueprint $table)
        {   
            $table->float('min')->nullable()->default(0)->change();
            $table->float('max')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions_rages', function(Blueprint $table)
        {   
            $table->integer('min')->nullable()->change();
            $table->integer('max')->nullable()->change();
        });
        
    }
}
