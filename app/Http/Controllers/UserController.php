<?php

namespace App\Http\Controllers;

class UserController extends RestiController
{
    public function sayHello()
    {
        $response = [
            'message' => 'Selamat Pagi'
        ];

        return response()->json($response);
    }
}
