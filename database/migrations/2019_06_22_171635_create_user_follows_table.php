<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('follower');
            $table->string('followee');
            $table->timestamps();
        });

        Schema::table('user_follows', function (Blueprint $table) {
            $table->foreign('follower')->references('uuid')->on('users');
            $table->foreign('followee')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_follows');
    }
}
