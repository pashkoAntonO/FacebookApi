<?php

namespace App\Http\Controllers\FaceBook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Facebook\Authentication\AccessToken;

class FacebookController extends Controller
{
   protected function  getTokent() : Facebook
    {

        $fb = new Facebook([
            'app_id' => env('APP_ID'),
            'app_secret' => env('APP_SECRET'),
            'default_graph_version' => 'v2.10',
        ]);

        return $fb;
    }



}
