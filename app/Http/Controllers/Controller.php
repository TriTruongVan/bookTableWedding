<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function okRes($data = [], $message = "Success")
    {
        return response()->json([
            "status" => "OK",
            "message" => $message,
            "data" => $data,
        ]);
    }

    protected function badRequestRes($message = "Bad request")
    {
        return response()->json([
            "status" => "NG",
            "message" => $message,
        ], 400);
    }

    protected function failedValidationRequestRes($message = "Bad request")
    {
        return response()->json([
            "status" => "NG",
            "errors" => $message,
        ], 422);
    }

    protected function unauthorizedRes($message = "Unauthorized")
    {
        return response()->json([
            "status" => "NG",
            "message" => $message,
        ], 401);
    }

    protected function notFoundRes($message = "Not found")
    {
        return response()->json([
            "status" => "NG",
            "message" => $message,
        ], 404);
    }

    protected function forbiddenRes($message = "Forbidden")
    {
        return response()->json([
            "status" => "NG",
            "message" => $message,
        ], 403);
    }
}
