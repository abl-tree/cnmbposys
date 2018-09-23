<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_info_id');
            $table->foreign('user_info_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->unsignedInteger('benefit_id');
            $table->foreign('benefit_id')->references('id')->on('benefits')->onDelete('cascade');
            $table->integer('id_number')->nullable();
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
        Schema::dropIfExists('user_benefits');
    }
}
