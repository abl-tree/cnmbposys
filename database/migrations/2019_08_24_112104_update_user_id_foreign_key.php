<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserIdForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['approved_by']);
            $table->foreign('user_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('user_infos')->onDelete('cascade');
        });

        Schema::table('request_schedules', function (Blueprint $table) {
            $table->dropForeign(['applicant']);
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['managed_by']);
            $table->foreign('applicant')->references('id')->on('user_infos')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('user_infos')->onDelete('cascade');
            $table->foreign('managed_by')->references('id')->on('user_infos')->onDelete('cascade');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['generated_by']);
            $table->foreign('user_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->foreign('generated_by')->references('id')->on('user_infos')->onDelete('cascade');
        });

        Schema::table('leave_credits', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('user_infos')->onDelete('cascade');
        });

        Schema::table('leave_slots', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('user_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
