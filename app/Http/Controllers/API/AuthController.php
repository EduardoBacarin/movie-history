<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller {

    public function login(Request $request) {
        $user = new User();
        $getUser = $user->get(hash("sha256", "email:$request->email"));
        if (!$getUser['success'] || !Hash::check($request->password, $getUser['data']['password'])) {
            return response()->json(["success" => false, "message" => "User or password incorrect", "code" => 404], 404);
        }
        return response()->json(["success" => true, "message" => "Token generated", "token" => $this->generateToken($getUser['data'])], 201);
    }

    public function logout(Request $request){
        Redis::delete("token:".$request->bearerToken());
        return response()->json(["success" => true, "message" => "Logged out"], 200);
    }

    private function generateToken($user) {
        $user["token_generated_at"] = time();
        $token = hash("sha256", $user);
        Redis::set("token:$token", json_encode($user->toArray()), "EX", env("TOKEN_LIFETIME"));
        return $token;
    }
}
