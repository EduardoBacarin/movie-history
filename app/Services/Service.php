<?php

namespace App\Services;

class Service {

    public function responsePattern($success, $code){
        return ["success" => $success, "code" => $code];
    }
}
