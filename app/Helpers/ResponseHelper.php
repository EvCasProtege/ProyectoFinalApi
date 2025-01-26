<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function formatResponse($code, $message, $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}