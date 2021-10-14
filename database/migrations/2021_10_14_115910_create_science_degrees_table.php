<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScienceDegreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('science_degrees', function (Blueprint $table) {
            $table->id();
            $table->string('short_title');
            $table->string('title');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('academic_titles', function (Blueprint $table) {
            $table->id();
            $table->string('short_title');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
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
        Schema::dropIfExists('science_degrees');
    }
}
