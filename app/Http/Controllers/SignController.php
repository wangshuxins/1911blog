<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignController extends Controller
{
    public function sign(request $request){
        $key = "1911php";
        $data = $request->get("data");
        $sign = $request->get("sign");
        $sign_str1 = md5($data . $key);
        if($sign_str1 == $sign){
            echo "验签成功";
        }else{
            echo "验签失败";
        }

    }
    public function sign1(){
        $sign = request()->get("sign");
        $data = request()->get("data");
        $sign2 = base64_decode($sign);
        $one_pub_key_counts = file_get_contents(storage_path("keys/1911_pub.key"));
        $one_pub_key = openssl_get_publickey($one_pub_key_counts);
        $status = openssl_verify($data,$sign2,$one_pub_key,OPENSSL_ALGO_SHA1);
        if($status){
            echo "验签成功";
        }else{
            echo "验签失败";
        }
    }
    public function hide(){
        if(isset($_SERVER['HTTP_TOKEN'])){
            $uid = $_SERVER['HTTP_UID'];
            $token = $_SERVER['HTTP_TOKEN'];
        }else{
            echo "授权失败";exit;
        }
        echo $uid;
        echo "<br>";
        echo $token;
    }
}
