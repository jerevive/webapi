<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 11:52
 */

namespace app\api\exception;


class CategoryException
{
    public $code = 404;

    public $errorCode = 50000;

    public $msg = '指定的分类资源不存在，请检查参数';
}