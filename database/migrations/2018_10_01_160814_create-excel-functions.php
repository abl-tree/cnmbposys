<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExcelFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_functions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('formula1');
            $table->string('formula2');
        });

        DB::table('excel_functions')->insert(
            array(
                'formula1' => '"=INDEX(Position!$D$2:$D$100,MATCH([Position],Position!$C$2:$C$100,0),0)',
                'formula2' => '"=INDEX(Position!$B$2:B$100,MATCH([Position ID],Position!$A$2:$A$100,0),0)',
                'formula3' => '"=INDEX(Position!$B$2:B$100,MATCH([Position ID],Position!$A$2:$A$100,0),0)',

            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
