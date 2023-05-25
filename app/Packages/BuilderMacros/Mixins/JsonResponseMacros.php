<?php

namespace App\Packages\BuilderMacros\Mixins;

use Illuminate\Support\Facades\Response;

class JsonResponseMacros extends MixinsAbstract
{

    protected static $parentClass = Response::class;

    protected function success(): \Closure
    {
        return fn($message, $data = []) => response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    protected function message(): \Closure
    {
        return fn($message, $success = true) => response()->json([
            'status' => $success,
            'message' => $message,
        ]);
    }

    protected function error(): \Closure
    {
        return function ($message, $statusCode, $data = []) {
            $response = [
                'status' => false,
                'message' => $message,
            ];

            if (count($data) > 0) {
                $response = array_merge($response, $data);
            }

            return response()->json($response, $statusCode);
        };
    }

}
