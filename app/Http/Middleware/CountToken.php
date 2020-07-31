<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class CountToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uid = $request->get("uid");
        $url = $_SERVER["REQUEST_URI"];
        $data = strpos($url,"?");
        if($data){
            $url = substr($url,0,$data);
        }
        $key = "user".$uid;
        Redis::zincrby($key,1,$url);
        return $next($request);
    }
}
