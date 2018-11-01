<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 15:38
 */

namespace app\exception;


class WxException extends BaseException
{
    public $code = 404;

    public $errorCode = 999;

    public $msg = '请求微信失败';
}