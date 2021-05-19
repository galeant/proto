<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentReportDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_report_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assessment_id');
            $table->integer('nilai');
            $table->string('penilai');
            $table->string('posisi_penilai');
            // $table->string('aspek');
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
        Schema::dropIfExists('assessment_report_detail');
    }
}
