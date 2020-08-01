<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get("/infos",function(){
    phpinfo();
});
Route::get("test3","TestController@test3");
Route::get("/info","TestController@info");
Route::get("/redis","TestController@redis");
Route::get("/redis1","TestController@redis1");
Route::get("/encrypt","TestController@encrypt");
Route::any("/rsa","TestController@rsa");
Route::get("/has","HasController@has");
Route::get("/gethas","HasController@gethas");
Route::get("/fang","HasController@fang");
Route::get("/blacklist","HasController@blacklist");
Route::get("token1","HasController@token1")->middleware("token","view");
Route::post("token2","HasController@token2");
Route::get("token3","HasController@token3")->middleware("token","login");
Route::get("sign","SignController@sign");
Route::get("sign1","SignController@sign1");
Route::get("hide","SignController@hide");
//H5
Route::post("/log","Admin\LoginController@login");
Route::post("/reg","Admin\LoginController@register");
Route::get("/goshop/{goods_id}","Admin\AdminController@goshop");

