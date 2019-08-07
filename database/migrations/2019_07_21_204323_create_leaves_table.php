<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('start_event');
            $table->dateTime('end_event');
            $table->string('leave_type');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('generated_by')->nullable();
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('allowed_access')->nullable();
            $table->foreign('allowed_access')->references('id')->on('access_levels')->onDelete('cascade');
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
        Schema::dropIfExists('leaves');
    }
}
