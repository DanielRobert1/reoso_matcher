<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FallbackController extends Controller
{

    use ApiResponse;

    /**
     * @return JsonResponse
     */
    final public function missing(): JsonResponse
    {
        return $this->sendError("The resource you're looking for was not found");
    }
}
