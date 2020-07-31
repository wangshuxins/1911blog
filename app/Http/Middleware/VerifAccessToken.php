<?php

namespace App\Http\Middleware;

use Closure;
use App\Login AS LoginModel;
use App\Token AS TokenModel;
class VerifAccessToken
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
        $token = $request->get('token');
        if(empty($token))
        {

            $response = [
                'erron' => 400003,
                'msg' => '未授权'
            ];
            return response()->json($response);
        }
        $t = TokenModel::where(["token"=>$token])->first();
        if(!$t){
            $response = [
                "errno" => "40003",
                "msg" => "token无效"
            ];
            return response()->json($response);
        }

        $request->attributes->add(["uid"=>$t->uid]);
        return $next($request);
    }

}
