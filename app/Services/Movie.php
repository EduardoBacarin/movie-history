<?php

namespace App\Services;

class Movie extends Service {
    public function getById($id){
        $sendRequest = $this->sendRequest(env('OMDB_SERVER'), "/?apikey=" . env("OMDB_KEY") . "&i=" . $id, 'GET');
        if ($sendRequest->ok() && $sendRequest->json("Response") == "True"){
            return $this->responsePattern(true, 200, $sendRequest->json());
        }else{
            return $this->responsePattern(false, 404);
        }
    }

    public function getByName($name){
        $sendRequest = $this->sendRequest(env('OMDB_SERVER'), "/?apikey=" . env("OMDB_KEY") . "&t=" . $name, 'GET');
        if ($sendRequest->ok() && $sendRequest->json("Response") == "True"){
            return $this->responsePattern(true, 200, $sendRequest->json());
        }else{
            return $this->responsePattern(false, 404);
        }
    }
}
