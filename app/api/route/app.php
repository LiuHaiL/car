<?php

use think\facade\Route;

Route::group(function () {
  //登录OR注册
  Route::post('checkIn', 'User/checkIn');
  //退出登录
  Route::post('logout', 'User/logout');
  //初始化
  Route::get('index', 'Index/index');
  //上传
  Route::post('upload','Ajax/upload');
  //用户实名认证
  Route::post('user_auth','User/userRealNameAuth');
  //获取用户信息
  Route::get('get_user_info', 'User/getUserInfo');
  
})->allowCrossDomain();
