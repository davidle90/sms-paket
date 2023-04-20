<?php

namespace Rocketlabs\Sms\App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;

class ResponseController extends Controller
{

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){

            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}