<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function sendSuccess($data = null)
    {
        return response()->json(
            [
                'status_code' => 200, 
                'status_message' => 'success',
                'data' => $data
            ], 200);
    }
    public function sendFailed($data = null)
    {
        return response()->json(
            [
                'status_code' => 400, 
                'status_message' => 'error',
                'data' => $data
            ], 400);
    }
}
