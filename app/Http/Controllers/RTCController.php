<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RTCController extends Controller
{
    public function select(Request $request, $select){
        // dd($select); 
        switch($select){
            case 'receiver':
                $identifier = NULL;
            break;
            case 'caller1':
                $identifier = 'uuuuussssrrra1';
            break;
            case 'caller2':
                $identifier = 'uuuuussssrrra2';
            break;
        }
        return view('kadal',[
            'select' => $select,
            'identifier' => $identifier
        ]);
    }
}
