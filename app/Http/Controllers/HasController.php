<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Login AS LoginModel;
use App\Token AS TokenModel;
class HasController extends Controller
{
    public function has(){
        $myshop = "myshop";
        $str = "1";
        Redis::lpush($myshop,$str);
        $key = Redis::llen($myshop);
        echo "添加库存".$key."件商品";

    }
    public function gethas()
    {
        $myshop = "myshop";
        $key = Redis::lrange($myshop, 1, -1);
        $key = Redis::llen($myshop);
        if ($key) {
           echo "商品还有".$key."件";
           Redis::lpop($myshop);
       }
        if(empty($key)){
            echo "商品已卖完";exit;
        }
    }
    public function fang(Request $request){

        $id = $request->get("id");
        if(empty($id)){
            echo "id为空";exit;
        }
        $key = "user";
        $token = Str::random(32);
        $dd = Redis::zincrby($key,1,$id);
        $count = Redis::zcount($key,$id,$id);
        if($count>=1){
            exit;
        }else{
            if($dd>10){
                Redis::zadd($key,$id,$token);
            }else{
                echo "已经访问".$dd."次";
            }

        }
    }
    public function token1(){
        $token = request()->get('token');
        $t = TokenModel::where(["token"=>$token])->first();
        $user_info = LoginModel::find($t->uid);
        $response = [
            "errno" => 0,
            "msg" => "ok",
            "data"=>[
                "user_info"=> $user_info
            ]
        ];
        return response()->json($response);
    }
    public function token2(Request $request){

        $name = $request->post("name");
        $pass = $request->post("pass");
        //echo $password = password_hash($pass,PASSWORD_BCRYPT);exit;
        $u = LoginModel::where(["username"=>$name])->first();
        if($u){
            //验证密码
            if(password_verify($pass,$u->password)){
                //生成token
                $token = Str::random(32);
                $expirer_seconds = 7200;
                $data = [
                    "token"=>$token,
                    "uid" => $u->id,
                    "expirer_at" =>time()+$expirer_seconds
                ];
                //入库
                $tid =  TokenModel::insertGetId($data);
                $response = [
                    "error" => "0",
                    "msg" => "登陆成功",
                    "data"=>[
                        "token"=>$token,
                        "exprier_in" =>$expirer_seconds
                    ]
                ];
                $key = "user";
                $us = $u->id."s";
                $p = Redis::zincrby($key,1,$us);
                Redis::zadd($key,$p,$us);
                $count = Redis::zscore($key,$us);
                echo $count;
            }else{

                $response = [
                    "error" => "50001",
                    "msg" => "密码错误"
                ];
            }

        }else{
            //用户不存在
            $response = [
                "error" => "400001",
                "msg" => "用户不存在"
            ];

        }
       return $response;

    }
    public function token3(){
        $token = request()->get('token');
        $t = TokenModel::where(["token"=>$token])->first();
        $user_info = LoginModel::find($t->uid);
        $response = [
            "errno" => 0,
            "msg" => "ok",
            "data"=>[
                "user_info"=> $user_info
            ]
        ];
        return response()->json($response);
    }
}
