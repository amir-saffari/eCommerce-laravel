<?php

namespace App\Traits;

trait ApiResponses
{

    public function ok($message)
    {
        return response()->json([
            'message' => $message,
            'status' => 200,
        ], 200);
    }

    public function success($message , $statusCode = 200 , $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }

    public function error($message , $statusCode = 400 , $errors = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'errors' => $errors,
        ], $statusCode);
    }


}
