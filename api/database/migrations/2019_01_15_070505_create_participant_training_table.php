<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_training', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('training_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('participant_id');
            $table->tinyInteger('status');
            $table->integer('score')->nullable();
            $table->integer('attempt')->nullable();
            $table->unsignedInteger('examiner_id');
            $table->softDeletes();
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
        Schema::dropIfExists('participant_training');
    }
}
