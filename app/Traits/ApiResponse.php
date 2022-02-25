<?php 

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Send formatted error to client
     * @param array $result
     * @param $messagee
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data'    => $result,
        ], $code);
    }

    /**
     * Sends a raw message back to client
     * @param $messagee
     * @param int $code
     * @return JsonResponse
     */
    public function sendSuccess($message, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], $code);
    }

    /**
     * Send formatted error to client
     * @param $error
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $data = [],  $code = 404): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $error,
        ];

        if(!empty($data)){
            $response['data'] = $data;
        }
        return response()->json([
            $response
        ], $code);
    }
}