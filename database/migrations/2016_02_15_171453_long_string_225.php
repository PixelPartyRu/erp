<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LongString225 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function($table)
        {
            $table->string('name', 225)->change();
            $table->string('full_name', 225)->change();
        });
        Schema::table('debtors', function($table)
        {
            $table->string('name', 225)->change();
            $table->string('full_name', 225)->change();
        });
        Schema::table('deliveries', function($table)
        {
            $table->string('notes', 225)->change();
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
