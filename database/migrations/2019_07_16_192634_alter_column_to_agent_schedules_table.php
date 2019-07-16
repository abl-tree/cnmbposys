<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnToAgentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->dropColumn('overtime');
            $table->unsignedInteger('overtime_id')->after('end_event')->nullable();
            $table->foreign('overtime_id')->references('id')->on('overtime_schedules')->onDelete('cascade');
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
            $table->time('overtime')->after('end_event');
            $table->dropForeign(['overtime_id']);
            $table->dropColumn('overtime_id');
        });
    }
}
