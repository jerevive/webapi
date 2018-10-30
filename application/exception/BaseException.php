<?php
/**
 * Created by Jesse.
 * Date: 2018/10/30
 * Time: 13:50
 */

namespace app\exception;


use think\Exception;
use Throwable;

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

    public function __construct($params)
    {
        if(!is_array($params)) return;

        if(array_key_exists('msg', $params))
        {
            $this->msg = $params['msg'];
        }

        if(array_key_exists('code', $params))
        {
            $this->code = $params['code'];
        }

        if(array_key_exists('errorCode', $params))
        {
            $this->errorCode = $params['errorCode'];
        }
    }
}