<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $code
     * @param $message
     * @param $result
     * @return JsonResponse
     */
    public function json($code, $message = null, $result = null): JsonResponse
    {
        $response = [
            'status' => $code
        ];

        if ($message)
            $response['message'] = $message;

        if ($result)
            $response['result'] = $result;

        return response()->json($response, $code);
    }
}
