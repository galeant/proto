<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentReport extends Model
{
    use HasFactory;
    protected $table = 'assessment_report';
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany('App\Models\AssessmentReportDetail', 'assessment_report_id', 'id');
    }

    public function camera()
    {
        return $this->belongsTo('App\Models\Camera', 'camera_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id', 'id');
    }
}
