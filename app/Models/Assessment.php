<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;
    protected $table = 'assessment';
    protected $guarded = [];

    public function assessmentReportDetail()
    {
        return $this->hasMany('App\Models\AssessmentReportDetail', 'assessment_id', 'id');
    }
}
