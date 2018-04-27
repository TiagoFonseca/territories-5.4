<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsHouses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments_houses', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('assignment_id')->unsigned;
          $table->integer('house_id')->unsigned;
          $table->string('status');
          $table->string('notes')->nullable;
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
        Schema::drop('assignments_houses');
    }
}
