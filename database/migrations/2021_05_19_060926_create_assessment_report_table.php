<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_report', function (Blueprint $table) {
            $table->id();
            $table->string('staff_name');
            $table->bigInteger('wilayah_id');
            $table->bigInteger('cabang_id');
            $table->bigInteger('outlet_id');
            $table->bigInteger('camera_id');
            $table->integer('point_summary');
            $table->string('grade_summary');
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
        Schema::dropIfExists('assessment_report');
    }
}
