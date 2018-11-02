<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 16:43
 */

namespace app\api\exception;


class TokenException extends BaseException
{
    public $code = 401;

    public $errorCode = 10001;

    public $msg = 'Token已过期或无效Token';
}