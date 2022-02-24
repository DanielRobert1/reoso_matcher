<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FallbackController extends Controller
{
    /**
     * @return JsonResponse
     */
    final public function missing(): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => "The resource you're looking for was not found"
        ], 404);
    }
}
