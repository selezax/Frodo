<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frodo_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id')->unique() ;
            $table->string('title') ;
            $table->integer('refresh_interval') ;
            $table->dateTime('last_updated');
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
        Schema::drop('frodo_accounts');
    }
}
