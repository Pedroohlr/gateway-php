<?php

namespace App\Services;

use App\Http\Controllers\PushController;
use Illuminate\Http\Request;

class SendNotification
{
    public function __construct()
    {
    }

    /**
     * 
     */
    public function one(int $user_id, string $title, string $body, $url = '/')
    {
        $push = new PushController();
        $data = compact('user_id', 'title', 'body', 'url');
        $request = new Request();
        $request->merge($data);
        $push->sendToUser($request);
    }


    public function all(string $title, string $body, $url = '/')
    {
        $push = new PushController();
        $data = compact('title', 'body', 'url');
        $request = new Request();
        $request->merge($data);
        $push->sendAll($request);
    }
}