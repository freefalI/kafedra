<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('parent_name');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('academic_title_id');
            $table->unsignedBigInteger('science_degree_id');
            $table->unsignedBigInteger('position_id');
            $table->date('hire_date');
            $table->string('email');
            $table->string('dob');
            $table->string('phone');
            $table->string('employment_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
