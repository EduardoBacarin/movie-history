<?php

namespace App\Services;

use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\AuthController;

class User extends Service {

    public function create($data) {
        $id = hash("sha256", "email:" . $data["email"]);
        if (!ModelsUser::where('_id', $id)->first()) {
            ModelsUser::create($data);
            Log::channel('user')->info("User $id has been created", $data);
            return $this->responsePattern(true, 201);
        } else {
            return $this->responsePattern(false, 409);
        }
    }

    public function update($id, $data) {
        $find = ModelsUser::where('_id', $id)->first();
        if ($find) {
            $find->update($data);
            Log::channel('user')->info("User $id has been updated", $data);
            return $this->responsePattern(true, 200);
        } else {
            return $this->responsePattern(false, 404);
        }
    }

    public function updatePassword($id, $data) {
        $find = ModelsUser::where('_id', $id)->first();
        if ($find && Hash::check($data['old_password'], $find->password)) {
            $find->update(["password" => Hash::make($data['new_password'])]);
            Log::channel('user')->info("User's $id password has been updated");
            return $this->responsePattern(true, 200);
        } else {
            return $this->responsePattern(false, 404);
        }
    }

    public function delete($id) {
        $find = ModelsUser::where('_id', $id)->first();
        if ($find) {
            $find->delete();
            Log::channel('user')->info("User $id has been deleted", $find);
            return $this->responsePattern(true, 200);
        } else {
            return $this->responsePattern(false, 404);
        }
    }

    public function get($id) {
        $find = ModelsUser::where('_id', $id)->first();
        if ($find) {
            return $this->responsePattern(true, 200, $find);
        } else {
            return $this->responsePattern(false, 404);
        }
    }

    public function addToHistory($imdbId, $userId) {
        $movieService = new Movie();
        $getMovie = $movieService->getById($imdbId);
        if ($getMovie['success']) {
            if (ModelsUser::where("_id", $userId)->where(["movies.imdbID" => $imdbId])->first()) {
                return $this->responsePattern(false, 409);
            }
            ModelsUser::where("_id", $userId)->push("movies", $getMovie['data']);
            return $this->responsePattern(true, 201);
        } else {
            return $this->responsePattern(false, 404);
        }
    }

    public function removeFromHistory($imdbId, $userId) {
        if (ModelsUser::where("_id", $userId)->where(["movies.imdbID" => $imdbId])->first()) {
            ModelsUser::where("_id", $userId)->pull("movies", ["imdbID" => $imdbId]);
            return $this->responsePattern(true, 200);
        } else {
            return $this->responsePattern(false, 404);
        }
    }
}
