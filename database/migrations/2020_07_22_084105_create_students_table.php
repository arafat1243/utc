<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->uniqid();
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('nationality',50);
            $table->string('marital_status',50);
            $table->date('dob');
            $table->string('gender',20);
            $table->string('number',20);
            $table->string('emergency_number',20);
            $table->text('present_address');
            $table->text('permanent_address');
            $table->string('profession');
            $table->string('academic_status');
            $table->string('blood_group')->default(null);
            $table->string('institute_name');
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
        Schema::dropIfExists('students');
    }
}
