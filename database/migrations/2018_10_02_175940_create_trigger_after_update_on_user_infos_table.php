<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerAfterUpdateOnUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER trigger_after_update_on_user_infos AFTER UPDATE ON `user_infos` FOR EACH ROW
            BEGIN
                DECLARE isExists integer;
                SET @isExists := (SELECT count(alh.id) as count FROM access_level_hierarchies alh WHERE alh.parent_id = NEW.id GROUP BY alh.parent_id);

                IF @isExists && NEW.status="terminated" THEN
                    UPDATE access_level_hierarchies alh SET alh.parent_id=null WHERE alh.parent_id=NEW.id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `trigger_after_update_on_user_infos`');
    }
}
