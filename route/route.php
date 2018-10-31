<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//Route::get('api/v1/banner/:id', 'api/v1.Banner/getBanner');

Route::group('api/:version', function(){

    Route::get('banner/:id', 'api/:version.Banner/getBanner');

    Route::get('theme', 'api/:version.Theme/getSimpleList');
});
