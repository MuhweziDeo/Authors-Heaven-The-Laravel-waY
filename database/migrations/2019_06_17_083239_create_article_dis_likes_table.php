<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleDisLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_dis_likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('article_slug');
            $table->string('user_uuid');
            $table->timestamps();
            
        });
        Schema::table('article_dis_likes', function(Blueprint $table){
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
        Schema::dropIfExists('article_dis_likes');
    }
}
