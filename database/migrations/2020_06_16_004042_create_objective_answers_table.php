<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectiveAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        Schema::create('objective_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cbt_id');
            $table->unsignedBigInteger('objective_question_id');
            $table->unsignedBigInteger('option_id');
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
        Schema::dropIfExists('objective_answers');
    }
}
