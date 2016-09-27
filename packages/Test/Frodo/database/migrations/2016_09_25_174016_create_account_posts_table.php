<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frodo_account_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned() ;
            $table->string('post_id')->unique();
            $table->text('title') ;
            $table->dateTime('datetime');
            $table->longText('description');
            $table->integer('num_favorites');
            $table->integer('num_replies');
            $table->integer('num_retweets');

            $table->index('account_id');
            $table->foreign('account_id')->references('id')->on('frodo_accounts')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('frodo_account_posts');
    }
}
