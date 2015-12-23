<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code');
            $table->boolean('type')->default(false);
            $table->boolean('account')->default(false);
            $table->date('penalty');
            $table->boolean('second_pay')->default(false);
            $table->string('code_1c',25);
            $table->string('description',100);
            $table->boolean('active')->default(true);

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');;

            $table->timestamps();
            $table->date('date_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('agreements');
    }
}
