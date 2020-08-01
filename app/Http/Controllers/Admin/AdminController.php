<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Car;
use Illuminate\Support\Facades\Redis;
class AdminController extends Controller
{
    public function goshop($goods_id){
        $goods = Goods::find($goods_id);
        $data = [
            "name" =>$goods->goods_name,
            "image"=>$goods->goods_img,
            "quantity"=>1,
            "price"=>$goods->shop_price*0.8,
            "goods_id"=>$goods->goods_id,
            "time" => time()
        ];
        $add = Car::where("goods_id",'=',$goods->goods_id)->first();
        $car = new Car();
        if($add){
               $add = Car::where("goods_id",$goods_id)->increment("quantity",1);
              if($add){
                  $shop_price= $goods->shop_price;
                  $quantity = Car::where("goods_id",$goods->goods_id)->sum("quantity");
                  $count = $shop_price*$quantity*0.8;
                  Car::where("goods_id",$goods_id)->update(["price"=>$count,"time"=>time()]);
              }
        }else{
               $add = $car->insert($data);
        }
        if($add){
            return redirect("http://www.1911.com/car");
        }else{
            return redirect("http://www.1911.com/");
        }
    }
}
