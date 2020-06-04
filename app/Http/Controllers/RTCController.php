<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Message;

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

    public function saveM(Request $request){
        $data = Message::create([
            'to' => $request->to,
            'from' => $request->from,
            'type' => $request->type,
            'code' => $request->code,
            'message' => urldecode($request->message)
        ]);

        return response()->json($data,200);
    }

    public function checkM(Request $request){
        $data = Message::where('to',$request->to)->get();
        $message = [];
        foreach($data as $dt){
            $message[] = [
                'id' =>$dt->id,
                'from' => $dt->from,
                'type' => $dt->type,
                'code' => $dt->code,
                'message' => urldecode($dt->message)
            ];
            Message::where('id',$dt->id)->delete();
        }

        $length = false;
        if($message > 0){
            $length = true;
        }
        return response()->json([
            'result' => $length,
            'data' => $message
        ],200);
    }


    public function b(Request $request){
        $name = $request->input('name','bebek');
        return view('aaa.b',[
            'name' => $name
        ]);
    }
}
