<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVtoAtColumnToAgentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->dateTime('vto_at')->nullable()->after('conformance');
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
            $table->dropColumn('vto_at');
        });
    }
}
