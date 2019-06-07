<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_status_logs', function (Blueprint $table) {
            $table->string('type')->nullable()->after('status');
        });
        Schema::table('user_infos', function (Blueprint $table) {
            $table->string('type')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_status_logs', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
