<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNightTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('night_tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('action_type');
            $table->text('action_params')->nullable();
            $table->integer('passcode');
            $table->foreign('passcode')->references('id')->on('night_task_passcodes');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('night_tasks');
    }
}
