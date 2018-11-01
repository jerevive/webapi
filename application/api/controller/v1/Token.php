<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 13:56
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\Code;

class Token
{
    public function getToken(Code $validate, $code)
    {
        $validate->goCheck();
        $token = UserToken::instance($code)->get();
        return ['token' => $token];
    }
}