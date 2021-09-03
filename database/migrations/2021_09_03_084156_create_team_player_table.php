<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_player', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('team_id')->unsigned();
            $table->string('first_name', 64);
            $table->string('last_name', 64);
            $table->string('image_name', 64)->nullable();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('team')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_player');
    }
}
