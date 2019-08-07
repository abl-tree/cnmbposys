<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConformanceColumnToAgentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_schedules', function (Blueprint $table) {
            $table->decimal('conformance', 5, 2)->after('overtime_id')->nullable();
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
            $table->dropColumn('conformance');
        });
    }
}
