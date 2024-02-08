<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ToolsController extends Controller
{
    function ping(): JsonResponse
    {
        return response()->json(['message' => 'pong']);
    }
}
