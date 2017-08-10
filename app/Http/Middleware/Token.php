<?php

namespace App\Http\Middleware;
use Facebook\Facebook;
use Closure;
use Illuminate\Http\Request;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $fb = new Facebook([
            'app_id' => '1905935933008272',
            'app_secret' => '8db6ecb6a48afcb79fd5dec689d2c9e8',
            'default_graph_version' => 'v2.10',
        ]);


        return $next($fb);
    }
}
