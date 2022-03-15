<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiBaseController extends Controller
{
    public function successResponse($data = [], $code = Response::HTTP_OK)
    {
        return \response()->json([
            'success' => true,
            'data' => $data,
        ], $code);
    }

    public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST)
    {
        return \response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
