<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('round');
            $table->integer('game_id');
            $table->integer('challenge_id');
            $table->integer('player1_id');
            $table->integer('player2_id');
            $table->integer('winner_id')->nullable(); //1 or 2
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rounds');
    }
}
