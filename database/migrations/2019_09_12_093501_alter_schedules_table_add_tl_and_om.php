<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchedulesTableAddTlAndOm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->unsignedInteger('tl_id')->after('overtime_id')->nullable()->after('user_id');
            $table->foreign('tl_id')->references('id')->on('user_infos')->onDelete('cascade');
            $table->unsignedInteger('om_id')->after('overtime_id')->nullable()->after('tl_id');
            $table->foreign('om_id')->references('id')->on('user_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->dropForeign(['tl_id']);
            $table->dropColumn('tl_id');
            $table->dropForeign(['om_id']);
            $table->dropColumn('om_id');
        });
    }
}
