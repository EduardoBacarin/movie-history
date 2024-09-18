<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class UserTest extends TestCase {

    public function test_create_user(): void {
        User::where(["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5"])->delete();
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => "123qwe"
        ];
        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(201);
        $response->assertJson(["success" => true, "code" => 201]);
        $this->assertDatabaseHas("users", ["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5", "name" => "John Doe", "email" => "john@doe.com"]);
        User::where(["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5"])->delete();
    }

    public function test_login_user(): void {
        User::where(["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5"])->delete();
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => "123qwe"
        ];
        User::create($data);
        $loginRequest = [
            "email" => $data['email'],
            "password" => $data['password'],
        ];
        $response = $this->post('/api/auth/login', $loginRequest);
        $response->assertStatus(201);
        $response->assertJsonFragment(["success" => true, "message" => "Token generated"]);
        $this->assertNotEmpty($response->json('token'));
        $getToken = Redis::get("token:".$response->json('token'));
        $this->assertNotEmpty($getToken);
        User::where(["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5"])->delete();
    }

    public function test_logout_user(): void {
        User::where(["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5"])->delete();
        $data = [
            "name" => "John Doe",
            "email" => "john@doe.com",
            "password" => "123qwe"
        ];
        User::create($data);
        $loginRequest = [
            "email" => $data['email'],
            "password" => $data['password'],
        ];
        $response = $this->post('/api/auth/login', $loginRequest);
        $logout = $this->withToken($response->json('token'))->delete('/api/auth/logout');
        $logout->assertJson(['success' => true, 'message' => 'Logged out']);
        $logout->assertStatus(200);
        $getToken = Redis::get("token:".$response->json('token'));
        $this->assertEmpty($getToken);
        User::where(["_id" => "ecab04f45bd75691d932e0d8b96c9e07cbd737a207c57fe950fd46803fb383f5"])->delete();
    }
}