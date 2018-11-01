<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 14:33
 */
use think\facade\Env;

return [
    'app_id' => Env::get('APP_ID'),
    'app_secret' => Env::get('APP_SECRET'),
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code'
];