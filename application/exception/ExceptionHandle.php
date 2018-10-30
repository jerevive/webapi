<?php
/**
 * Created by Jesse.
 * Date: 2018/10/30
 * Time: 13:55
 */

namespace app\exception;


use Exception;
use think\exception\Handle;
use think\facade\Request;

class ExceptionHandle extends Handle
{
    private $errorCode;
    private $code;
    private $msg;

    public function render(Exception $e)
    {
        if($e instanceof BaseException)
        {
            $this->code = $e->code;
            $this->errorCode = $e->errorCode;
            $this->msg = $e->msg;
        }else
        {
            $this->code = 500;
            $this->errorCode = 999;
            $this->msg = '服务器内部错误';
        }
        return json([
            'errorCode' => $this->errorCode,
            'msg'  => $this->msg,
            'url'  => Request::url(true)
        ], $this->code);
    }
}