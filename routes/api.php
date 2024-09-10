<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("/auth")->group(function(){
    Route::post("/register", [UserController::class, "create"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::delete("/logout", [AuthController::class, "logout"])->middleware("api-token");
});

Route::prefix("/user")->middleware("api-token")->group(function(){
    Route::get("/", [UserController::class, "get"]);
    Route::patch("/", [UserController::class, "update"]);
    Route::patch("/password", [UserController::class, "password"]);
    Route::delete("/", [UserController::class, "destroy"]);
});

Route::prefix("/movie")->middleware("api-token")->group(function(){
    Route::get("/", [MovieController::class, "get"]);
    Route::post("/", [MovieController::class, "addMovieToHistory"]);
    Route::delete("/", [MovieController::class, "removeMovieFromHistory"]);
});
