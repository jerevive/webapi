<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 10:56
 */

namespace app\exception;


class ProductException extends BaseException
{
    public $code = 404;

    public $errorCode = 40000;

    public $msg = '指定的商品资源不存在，请检查参数';
}