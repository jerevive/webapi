<?php
/**
 * Created by Jesse.
 * Date: 2018/10/30
 * Time: 13:50
 */

namespace app\exception;


use think\Exception;

class BaseException extends Exception
{
    /**
     *  HTTP状态码
     */
    public $code;

    /**
     *  错误消息
     */
    public $msg;

    /**
     *  错误码
     */
    public $errorCode;
}