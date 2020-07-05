<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectiveTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objective_tests', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->datetime('starttime');
            $table->bigInteger('duration');
            $table->datetime('deadline');
            $table->unsignedbiginteger('classroom_id');
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
        Schema::dropIfExists('objective_tests');
    }
}
