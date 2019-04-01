<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserReportsReponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reports_response', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_response_id');
            $table->foreign('user_response_id')->references('id')->on('user_reports')->onDelete('cascade');
            $table->longText('commitment');
            $table->string('status');
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
         Schema::dropIfExists('user_reports_response');
    }
}
