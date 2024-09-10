<?php

namespace App\Http\Controllers\API;

use App\Services\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

class UserController extends Controller
{
    public function create(CreateUserRequest $request){
        try {
            $service = new User();
            $create = $service->create($request->all());
            return response()->json($create, $create['code']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "An error has ocurred", 'code' => 400], 400);
        }
    }

    public function get(Request $request){
        try {
            $service = new User();
            $get = $service->get($this->getLoggedUserId($request));
            return response()->json($get, $get['code']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "An error has ocurred", 'code' => 400], 400);
        }
    }

    public function update(UpdateUserRequest $request){
        try {
            $service = new User();
            $update = $service->update($this->getLoggedUserId($request), $request->all());
            return response()->json($update, $update['code']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "An error has ocurred", 'code' => 400], 400);
        }
    }

    public function password(UpdateUserPasswordRequest $request){
        try {
            $service = new User();
            $update = $service->updatePassword($this->getLoggedUserId($request), $request->all());
            return response()->json($update, $update['code']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "An error has ocurred", 'code' => 400], 400);
        }
    }

    public function destroy(UpdateUserRequest $request){
        try {
            $service = new User();
            $delete = $service->delete($this->getLoggedUserId($request));
            $auth = new AuthController();
            $auth->logout($request);
            return response()->json($delete, $delete['code']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "An error has ocurred", 'code' => 400], 400);
        }
    }
}
