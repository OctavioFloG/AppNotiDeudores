<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard Admin',
            'user' => auth('sanctum')->user(),
            'data' => ['status' => 'ok']
        ]);
    }
}
