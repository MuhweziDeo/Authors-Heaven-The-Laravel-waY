<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('article_slug');
            $table->string('user_uuid');
            $table->text('comment_body');
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table){
            $table->foreign('article_slug')->references('slug')->on('articles')
                    ->onDelete('cascade');
            $table->foreign('user_uuid')->references('uuid')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
