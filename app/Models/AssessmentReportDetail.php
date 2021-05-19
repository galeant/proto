<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentReportDetail extends Model
{
    use HasFactory;
    protected $table = 'assessment_report_detail';
    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo('App\Models\AssessmentReport', 'assessment_report_id', 'id');
    }

    public function assessment()
    {
        return $this->belongsTo('App\Models\Assessment', 'assessment_id', 'id');
    }
}
