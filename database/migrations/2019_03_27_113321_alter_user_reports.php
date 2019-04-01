<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('user_reports', function (Blueprint $table) {
            $table->unsignedInteger('sanction_type_id');
            $table->foreign('sanction_type_id')->references('id')->on('sanction_type')->onDelete('cascade');
            $table->unsignedInteger('sanction_level_id');
            $table->foreign('sanction_level_id')->references('id')->on('sanction_level')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('user_reports', function (Blueprint $table) {
            $table->unsignedInteger('sanction_type_id');
            $table->unsignedInteger('sanction_level_id');
        });
    }
}
