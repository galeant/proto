<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;
    protected $table = 'camera';
    protected $guarded = [];

    public function assessmentReport()
    {
        return $this->hasMany('App\Models\AssessmentReport', 'camera_id', 'id');
    }
}
