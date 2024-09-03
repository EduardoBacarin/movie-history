<?php

namespace App\Services;

use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\DB;

class User extends Service {

    public function create($data) {
        $id = hash("sha256", "email:" . $data["email"]);
        if (!ModelsUser::where('_id', $id)->first()) {
            ModelsUser::create($data);
            return $this->responsePattern(true, 201);
        } else {
            return $this->responsePattern(false, 409);
        }
    }

    public function update($id, $data) {
        if (ModelsUser::where('_id', $id)->first()) {
            ModelsUser::where('_id', $id)->update($data);
            return $this->responsePattern(true, 200);
        } else {
            return $this->responsePattern(false, 404);
        }
    }
}
