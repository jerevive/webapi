<?php
/**
 * Created by Jesse.
 * Date: 2018/10/31
 * Time: 20:34
 */

namespace app\exception;


class ThemeException extends BaseException
{
    public $code = 300;

    public $errorCode = 30000;

    public $msg = '指定的主题资源不存在，请检查主题ID';

}