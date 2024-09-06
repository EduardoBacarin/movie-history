<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getLoggedUserId(Request $request){
        $tokenData = Redis::get("token:".$request->bearerToken());
        if (!$tokenData){
            return false;
        }
        return json_decode($tokenData)->_id;
    }
}
