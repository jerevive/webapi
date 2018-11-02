<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 9:30
 */

namespace app\api\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;

    public $errorCode = 10001;

    public $msg = '权限不足';
}