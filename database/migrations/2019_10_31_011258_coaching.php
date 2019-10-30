<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class coaching extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaching', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("filed_to");
            $table->foreign('filed_to')->references("id")->on("user_infos")->onDelete("cascade");
            $table->unsignedInteger("filed_by");
            $table->foreign('filed_by')->references("id")->on("user_infos")->onDelete("cascade");
            $table->unsignedInteger("sched_id")->nullable();
            $table->foreign('sched_id')->references("id")->on("agent_schedules")->onDelete("cascade");
            $table->string('status')->nullable();
            $table->string('type')->nullable();
            $table->string('remarks')->nullable();
            $table->string('img_proof_url')->nullable();
            $table->string('filed_to_action')->nullable();
            $table->unsignedInteger("verified_by")->nullable();
            $table->foreign('verified_by')->references("id")->on("user_infos")->onDelete("cascade");
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
        Schema::dropIfExists('coaching');
    }
}
