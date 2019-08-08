<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNcnsColumnToAgentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->boolean('remarks')->after('overtime_id');
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
            $table->dropColumn('remarks');
        });
    }
}
