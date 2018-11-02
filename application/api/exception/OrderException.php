<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 13:12
 */

namespace app\api\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}