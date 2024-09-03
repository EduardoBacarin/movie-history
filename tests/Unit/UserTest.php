<?php

namespace Tests\Unit;

use App\Http\Requests\CreateUserRequest;
use App\Models\User as ModelsUser;
use Tests\TestCase;
use App\Services\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase {
    /**
     * A basic unit test example.
     */
    public function test_create_user_success(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => "johndoe123"
        ];

        ModelsUser::where("_id", hash("sha256", "email:" . $data["email"]))->delete();
        $service = new User();
        $create = $service->create($data);
        $this->assertTrue($create['success']);
        $this->assertEquals(201, $create['code']);
        ModelsUser::where("_id", hash("sha256", "email:" . $data["email"]))->delete();
    }


    public function test_update_user_success(): void {
        DB::collection("users")->where("_id", "idToUpdate")->delete();
        DB::collection("users")->insert([
            "_id" => "idToUpdate",
            "name" => "John Doe",
            "email" => "john@doe.com"
        ]);

        $data = [
            "name" => "John Doe",
            "email" => "johndoe@gmail.com",
        ];

        $service = new User();
        $create = $service->update("idToUpdate", $data);
        $this->assertTrue($create['success']);
        $this->assertEquals(200, $create['code']);
        $getData = ModelsUser::where("_id", "idToUpdate")->first();
        $this->assertEquals($data['email'], $getData->email);
        DB::collection("users")->where("_id", "idToUpdate")->delete();
    }

    public function test_validate_create_user_request_success(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => "johndoe123"
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    public function test_validate_create_user_request_failed_because_pass_is_empty(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => ""
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(["password" => ["The password field is required."]], $validator->messages()->messages());
    }

    public function test_validate_create_user_request_failed_because_name_is_less_than_3_digits(): void {
        $data = [
            "name" => "Jo",
            "email" => "john@doe.com",
            "password" => "johndoe123"
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(["name" => ["The name field must be at least 3 characters."]], $validator->messages()->messages());
    }

    public function test_validate_create_user_request_failed_because_email_is_invalid(): void {
        $data = [
            "name" => "John Doe",
            "email" => "john.com",
            "password" => "johndoe123"
        ];

        $request = new CreateUserRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
        $this->assertEquals(["email" => ["The email field must be a valid email address."]], $validator->messages()->messages());
    }
}
