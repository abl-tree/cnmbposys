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
            $table->string('formula3');
        });

        DB::table('excel_functions')->insert(
            array(
                'formula1' => '"=IF([Position]<>"",INDEX(Position!D:D,MATCH([Position],Position!C:C,0),0),"")',
                'formula2' => '"=IF([Position]<>"",INDEX(Position!B:B,MATCH([Position ID],Position!A:A,0),0),"")',
                'formula3' => '"=IF([Parent Name]<>"",INDEX(ParentList!A:A,MATCH([Parent Name],ParentList!B:B,0),0),"")',
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
