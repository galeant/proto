<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $tabel = 'message';
    protected $fillable = [
        'from','to','type','message','code'
    ];
}
