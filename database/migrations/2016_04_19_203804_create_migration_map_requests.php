<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMigrationMapRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_requests', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id')->unsigned;
          $table->integer('map_id')->unsigned;
          $table->string('status');
          $table->string('message')->nullable();
          $table->timestamp('requested_on')->nullable();
          $table->timestamp('responded_on')->nullable();
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
        Schema::drop('map_requests');
    }
}
