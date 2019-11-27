<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::domain('m',function(){
    Route::rule(':str', 'wap/index/index');
});
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
	'apicom/free' => 'apicom/stock/free',
    'stock/free' => 'stock/index/free',
    'stock/day' => 'stock/index/day',
    'stock/week' => 'stock/index/week',
    'stock/month' => 'stock/index/month',
    'login' => 'member/publics/login',
    'register'=>'member/publics/register',
    'backpasswd' => 'member/publics/backpasswd',
    'newpass' => 'member/publics/newpass',
    'signout' => 'member/publics/signout',
    'wap' => 'wap/index/index',
    'androiddownload' => 'cms/page/mobileDownload',
    '[handle]'  => [
        //'apply' =>['stock/index/applysave', ['method'=>'post']],
        'apply' =>['stock/handle/applysave', ['method'=>'get']],
    ],
];
