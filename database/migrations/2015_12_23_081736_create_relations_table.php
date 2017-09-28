<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->default(true);
            $table->float('rpp');
            $table->boolean('confedential_factoring')->default(false);
            $table->float('registry',50)->default(false);

            $table->integer('deferment');
            $table->boolean('deferment_start')->default(false);
            $table->string('deferment_type',10);

            $table->integer('waiting_period');
            $table->string('waiting_period_type',10);

            $table->integer('regress_period');
            $table->string('regress_period_type',10);

            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->bigInteger('original_document_id')->unsigned();
            $table->foreign('original_document_id')->references('id')->on('original_documents')->onDelete('cascade');

            $table->bigInteger('contract_id')->unsigned();
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');

            $table->bigInteger('debtor_id')->unsigned();
            $table->foreign('debtor_id')->references('id')->on('debtors')->onDelete('cascade');

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
        Schema::drop('relations');
    }
}
