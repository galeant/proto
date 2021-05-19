<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branch';
    protected $guarded = [];


    public function assessmentReport()
    {
        return $this->hasMany('App\Models\AssessmentReport', 'branch_id', 'id');
    }
}
