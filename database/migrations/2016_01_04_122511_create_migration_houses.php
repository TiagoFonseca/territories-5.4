<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMigrationHouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('houses', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('slip_id');
        $table->integer('street_id');
        $table->string('number');
        $table->string('type');
        $table->string('status');
        $table->string('description');
        $table->string('bellflatno')->nullable();
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
          Schema::drop('houses');
    }
}
