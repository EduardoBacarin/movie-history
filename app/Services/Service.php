<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Service {

    public function responsePattern($success, $code, $data = []) {
        $response = ["success" => $success, "code" => $code];
        if (!empty($data)) $response['data'] = $data;
        return $response;
    }

    public function sendRequest($uri, $endpoint, $method, $data = [], $headers = []) {
        $url = $uri . $endpoint;
        switch ($method) {
            case 'POST':
                $request = Http::withHeaders($headers)->post($url, $data);
                break;
            case 'GET':
                $request = Http::withHeaders($headers)->get($url);
                break;
            case 'PUT':
                $request = Http::withHeaders($headers)->put($url, $data);
                break;
            case 'PATCH':
                $request = Http::withHeaders($headers)->patch($url, $data);
                break;
            case 'DELETE':
                $request = Http::withHeaders($headers)->delete($url, $data);
                break;
        }
        return $request;
    }
}
