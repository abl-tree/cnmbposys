<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_reports_id');
            $table->foreign('user_reports_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->unsignedInteger('filed_by');
            $table->foreign('filed_by')->references('id')->on('user_infos')->onDelete('cascade');
            $table->longText('description');
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
         Schema::dropIfExists('user_reports');
    }
}
