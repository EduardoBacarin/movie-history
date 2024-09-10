<?php

namespace App\Http\Controllers\API;

use App\Services\User;
use App\Services\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MovieController extends Controller {
    public function get(Request $request) {
        try {
            $service = new Movie();
            if ($request->get("name")) {
                $get = $service->getByName($request->get("name"));
                Log::channel('user')->info("User " . $this->getLoggedUserId($request) . " searched movie by Name",["name" => $request->get("name")]);
                return response()->json($get, $get['code']);
            }
            if ($request->get("imdb")) {
                $get = $service->getById($request->get("imdb"));
                Log::channel('user')->info("User " . $this->getLoggedUserId($request) . " searched movie by ID", ["imdbId" => $request->get("id")]);
                return response()->json($get, $get['code']);
            }
            return response()->json(["success" => false], 404);
        } catch (\Throwable $th) {
            return response()->json(["success" => false], 400);
        }
    }

    public function addMovieToHistory(Request $request) {
        try {
            $service = new User();
            $add = $service->addToHistory($request->id, $this->getLoggedUserId($request));
            Log::channel('user')->info("User " . $this->getLoggedUserId($request) . " added Movie in History", ["imdbId" => $request->id]);
            return response()->json($add, $add['code']);
        } catch (\Throwable $th) {
            return response()->json(["success" => false], 400);
        }
    }

    public function removeMovieFromHistory(Request $request) {
        try {
            $service = new User();
            $remove = $service->removeFromHistory($request->id, $this->getLoggedUserId($request));
            Log::channel('user')->info("User " . $this->getLoggedUserId($request) . " removed Movie from History", ["imdbId" => $request->id]);
            return response()->json($remove, $remove['code']);
        } catch (\Throwable $th) {
            return response()->json(["success" => false], 400);
        }
    }
}
