<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{

    public function test3(){

        $url = "http://www.1911.com/test3";
        $response = file_get_contents($url);
        var_dump($response);
    }

    public function info(){
        echo 123;
    }
    public function redis(){
        $data = [
           "name"=>"guanyu",
            "sex"=>"nan",
            "age"=>"20"
        ];
        $key = "key";
        Redis::hmset($key,$data);

    }
    public function redis1(){

        $key = "key";
        $data = Redis::hgetall($key);
        echo "<pre>";print_r($data);echo "</pre>";

    }
    public function encrypt(){
         $data = "HELLOW";
         $method = "AES-256-CBC";
         $key = "usr";
         $iv = "aaaabbbbccccdddd";
         echo "原密:".$data."<br>"."<hr>";

         $enc_data = openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
         $b64_str = base64_encode($enc_data);
         echo "加密:". $b64_str."<br>"."<hr>";
        $enc_data = [
          "data"=>  $b64_str,
        ];
         $url="http://www.1911.com/decrypt";
        $ch=curl_init();
        //设置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $enc_data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //发送请求
        $response=curl_exec($ch);
        echo $response;
        //提示错误
        $errno=curl_errno($ch);
        if($errno){
            $errmsg=curl_error($ch);
            var_dump($errmsg);
        }
        curl_close($ch);
//        $dec_data = openssl_decrypt($enc_data,$method,$key,OPENSSL_RAW_DATA,$iv);
//        echo $dec_data;
    }
    public function rsa(){
        $data = "迪迦";
        echo "原密:".$data."<br>"."<hr>";
        $content = openssl_get_privatekey(file_get_contents(storage_path("keys/priv.key")));

        $priv_key = openssl_get_privatekey($content);
        openssl_private_encrypt($data,$enc_data,$priv_key);
        echo "加密:".$enc_data."<br>"."<hr>";
        $enc_data = [
            "data"=>  $enc_data,
        ];
        $url="http://www.1911.com/dersa";
        $ch=curl_init();
        //设置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $enc_data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //发送请求
        $response=curl_exec($ch);
        echo $response;
        //提示错误
        $errno=curl_errno($ch);
        if($errno){
            $errmsg=curl_error($ch);
            var_dump($errmsg);
        }
        curl_close($ch);
        ####################################################################
        $one_pub_key = file_get_contents(storage_path("keys/1911_pub.key"));
        openssl_public_decrypt($response,$dec_data,$one_pub_key);
        echo "<br>";
        echo "<hr>";
        echo $dec_data;

    }
}
