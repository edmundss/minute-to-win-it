<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameChallengePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_game', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('game_id');
            $table->integer('challenge_id');
            $table->boolean('done')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('challenge_game');
    }
}
