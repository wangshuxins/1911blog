<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Login AS LoginModel;
use App\Token AS TokenModel;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    public function login(Request $request){
        $name = $request->post("username");
        $pass = $request->post("password");
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
        if($response['error']==0){
            return redirect("http://www.1911.com/");
        }

    }
    public function register(Request $request)
    {
        $username = $request->post("username");
        $email = $request->post("email");
        $password = $request->post("password");
        $repassword = $request->post("repassword");

        $login = LoginModel::where(["username"=>$username])->first();

        if($login){
            $response = [
                "error" => "1231",
                "msg" => "用户名已存在"
            ];
            return $response;
        }

        if(empty($username)){
            $response = [
                "error" => "1232",
                "msg" => "用户名不能为空"
            ];
            return $response;
        }
        if(empty($password)){
            $response = [
                "error" => "1233",
                "msg" => "密码不能为空"
            ];
            return $response;
        }
        if(empty($email)){
            $response = [
                "error" => "1234",
                "msg" => "邮箱不能为空"
            ];
            return $response;
        }
        if(empty($repassword)){
            $response = [
                "error" => "1231",
                "msg" => "确认密码不能为空"
            ];
            return $response;
        }
        if($password!= $repassword){
            $response = [
                "error" => "1231",
                "msg" => "确认密码与密码保持一致"
            ];
            return $response;
        }
        $pass = password_hash($password,PASSWORD_BCRYPT);

        $userInfo = [
            "username" => $username,
            "email" => $email,
            "password" => $pass,
            "time" => time()
        ];
        $id = LoginModel::insertGetId($userInfo);
        if ($id) {

            $response = [
                "error" => "0",
                "msg" => "ok"
            ];
            if($response['error']==0){
                return redirect("http://www.1911.com/log");
            }
        }
    }
}
