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
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('birthdate');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('contact_number');
            $table->string('address')->nullable();
            $table->double('salary_rate', 8, 2)->nullable();
            $table->string('image_ext')->nullable();
            $table->string('status')->nullable();
            $table->string('hired_date');
            $table->string('separation_date');
            $table->string('excel_hash');
            $table->string('p_email');
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
