<?php
/**
 * Created by Jesse.
 * Date: 2018/10/30
 * Time: 16:41
 */

namespace app\exception;



class ParameterException extends BaseException
{
    public $code = 400;

    public $errorCode = 10000;

    public $msg = '参数错误';

}