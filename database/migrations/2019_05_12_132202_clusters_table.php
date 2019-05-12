<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('om_id');
            $table->foreign('om_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->unsignedInteger('tl_id');
            $table->foreign('tl_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->unsignedInteger('agent_id');
            $table->foreign('agent_id')->references('id')->on('user_infos')->onDelete('cascade');
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
        Schema::dropIfExists('clusters');
    }
}
