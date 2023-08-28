<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Response
{
    public static function make($code, $message, $data = [])
    {
        if (in_array($code,  ['500'])) {
            Log::error($message . ' : action By ' . Convert::username(Auth::id()));
        }
        # response json
        return response()->json([
            'status' => $code === 200 ? true : false,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
