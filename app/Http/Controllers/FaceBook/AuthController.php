<?php

namespace App\Http\Controllers\FaceBook;


use App\Http\Controllers\Controller;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Facebook\Authentication\AccessToken;
class AuthController extends FacebookController
{
    public function getToken() : View
    {
        $fb =  $this->getTokent();

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email'];

        $loginUrl = $helper->getLoginUrl('http://localhost:8000/session', $permissions);

        return view('Facebook/Auth')->withLink($loginUrl);
    }

    function setSession(): RedirectResponse
    {

        session_start();

        $fb =  $this->getTokent();

        $helper = $fb->getRedirectLoginHelper();

        $accessToken = $this->checkToken($helper);



        if (isset($accessToken)) {
            if (isset($_SESSION['facebook_access_token'])) {

                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            } else {
                $_SESSION['facebook_access_token'] = (string)$accessToken;

                $oAuth2Client = $fb->getOAuth2Client();

                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

                $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;

                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            }
        }


            return redirect()->route('show');
        }




    private function checkToken($helper) : AccessToken
    {
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
            } else {
                header('HTTP/1.0 400 Bad Request');
            }
            return view('Facebook/Error')->withError('Graph returned an error: ');
        }

        return $accessToken;
    }

//    function getTokent() : Facebook
//    {
//
//        $fb = new Facebook([
//            'app_id' => env('APP_ID'),
//            'app_secret' => env('APP_SECRET'),
//            'default_graph_version' => 'v2.10',
//        ]);
//
//        return $fb;
//    }

}


