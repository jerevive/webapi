<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 20:24
 */

namespace app\exception;


class UserException extends BaseException
{

    public $code = 404;

    public $errorCode = 60000;

    public $msg = '指定用户不存在';
}