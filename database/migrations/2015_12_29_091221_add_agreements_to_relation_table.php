<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgreementsToRelationTable extends Migration
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
            $table->bigInteger('agreement_id')->unsigned();
            $table->foreign('agreement_id')->references('id')->on('agreements')->onDelete('cascade');
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
            $table->dropColumn('agreement_id');
            $table->dropForeign('relations_agreement_id_foreign');
        });
    }
}
