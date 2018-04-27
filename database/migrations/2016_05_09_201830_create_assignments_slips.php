<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsSlips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments_slips', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('assignment_id')->unsigned;
          $table->integer('slip_id')->unsigned;
          $table->string('shared');
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
        Schema::drop('assignments_slips');
    }
}
