<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('route_id');
            $table->foreign('route_id')->references('id')->on('route_lists')->onDelete('cascade');
            $table->unsignedInteger('access_id');
            $table->foreign('access_id')->references('id')->on('access_levels')->onDelete('cascade');
            $table->boolean('allowed')->default(1);
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
        Schema::dropIfExists('permissions');
    }
}
