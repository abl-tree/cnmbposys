<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use App\ExcelTemplateValidator as etv;
class CreateExcelTemplateValidatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_template_validators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template');
            $table->string('token');
            $table->timestamps();
        });
        //insert initial tokens
        etv::insert([
            [
                'template' => 'Add',
                'token' => Str::random(20),
            ],
            [
                'template' => 'Reassign',
                'token' => Str::random(20),
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excel_template_validators');
    }

    
}
