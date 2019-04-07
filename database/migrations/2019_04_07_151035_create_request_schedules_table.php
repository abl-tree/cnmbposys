<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('applicant');
            $table->foreign('applicant')->references('id')->on('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('title_id');
            $table->foreign('title_id')->references('id')->on('event_titles')->onDelete('cascade');
            $table->unsignedInteger('requested_by');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('managed_by');
            $table->foreign('managed_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('request_schedules');
    }
}