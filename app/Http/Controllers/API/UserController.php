<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(CreateUserRequest $request){
        try {
            $service = new User();
            return $service->create($request->all());
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "An error has ocurred", 'code' => 400], 400);
        }
    }
}
