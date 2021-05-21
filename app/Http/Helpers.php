<?php

use Illuminate\Support\Facades\Validator;

function validation($request, $rule)
{
    $validator = Validator::make($request->all(), $rule);
    if ($validator->fails()) {
        return [
            'status' => 'fail',
            'message' => $validator->errors()->first()
        ];
    } else {
        return [
            'status' => 'ok',
            'message' => ''
        ];
    }
}
