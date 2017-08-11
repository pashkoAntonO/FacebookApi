<?php

namespace App\Http\Controllers\FaceBook;


use App\Http\Controllers\Controller;
use Facebook\FacebookApp;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Illuminate\Http\Response;

class PostsController extends FacebookController
{
    public function posts()
    {
        session_start();

        $fb = $this->getTokent();

        if (isset($_SESSION['facebook_access_token'])) {
            $accessToken = $_SESSION['facebook_access_token'];
        }
        else{
            return view("Facebook/Error");
        }

            $fbApp = new FacebookApp(env('APP_ID'), env('APP_SECRET'));

            $request = new FacebookRequest($fbApp, $accessToken, 'GET', '/me/posts?limit=5');

            $response = $this->getResponse($request, $fb);

            $graphNode = $response->getGraphEdge();

            $likes = Array();        //Количество лайков
            $comment_count = Array(); //Количество комментриев
            $comments = Array();       //Комментарии
            $shares_count = Array();        // Раз поделились
            $pictures = Array();     //Картинка в посте
            $messages = Array();    //Сообщения в посте


            for ($i = 0; $i < 5; $i++) {
                $request = new FacebookRequest($fbApp, $accessToken, 'GET', '/' . $graphNode[$i]["id"] . '?fields=likes.summary(true),comments.summary(true),shares,picture,name');

                $response = $this->getResponse($request, $fb);

                $body = json_decode($response->getBody(), True);
                //Количество лайков 5 постов
                $likes[$i] = $body["likes"]["summary"]["total_count"];
                //Количество комментариев
                $comment_count[$i] = $body["comments"]["summary"]["total_count"];
                //Количество раз поделились
                if (isset($body['shares']["summary"]['total_count'])) {
                    $shares_count[$i] = $body['shares']["summary"]['total_count'];
                } else {
                    $shares_count[$i] = 0;
                }
                //Комментарии, текст
                for($j=0; $j < count($body["comments"]["data"]); $j++){
                    $comments[$i][$j] = $body['comments']["data"][$j]["message"];
                }
                //Картинка поста
                if (isset($body["picture"])) {
                    $pictures[$i] = $body["picture"];
                } else {
                    $pictures[$i] = "";
                }
                // сообщения
                if(isset($graphNode[$i]["message"])){
                    $messages[$i] = $graphNode[$i]["message"];
                }
                else{
                    $messages[$i] = "";
                }
            }

        $all = Array($messages, $likes, $comments, $comment_count, $pictures, $shares_count);

        return response()->json($all);
    }



    private function getResponse($request, $fb)
    {
        try {
            $response = $fb->getClient()->sendRequest($request);
        } catch (FacebookResponseException $e) {
            return view('Facebook/Error')->withError('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            return view('Facebook/Error')->withError('Facebook SDK returned an error: ' . $e->getMessage());
        }

        return $response;
    }

    public function show(){
        return view("Facebook/Posts");
    }

}
