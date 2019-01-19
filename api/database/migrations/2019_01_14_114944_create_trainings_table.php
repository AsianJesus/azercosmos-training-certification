<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->text('reference');
            $table->tinyInteger('status');
            $table->boolean('is_test_exam');
            $table->integer('question_number')->nullable();
            $table->integer('pass_score')->nullable();
            $table->integer('exam_time')->nullable();
            $table->unsignedInteger('tutorial_id')->nullable();
            $table->unsignedInteger('originator_id');
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
        Schema::dropIfExists('trainings');
    }
}
