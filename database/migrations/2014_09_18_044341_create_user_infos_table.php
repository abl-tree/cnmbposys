<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('user_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->double('salary_rate', 8, 2)->nullable();
            $table->string('image_ext')->nullable();
            $table->string('status')->nullable();
            $table->string('hired_date')->nullable();
            $table->string('separation_date')->nullable();
            $table->string('excel_hash')->nullable();
            $table->string('p_email')->nullable();
            $table->string('status_reason')->nullable();
            $table->timestamps();
        });
        
        DB::statement("ALTER TABLE user_infos ADD image LONGBLOB");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }

}
