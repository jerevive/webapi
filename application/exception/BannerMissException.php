<?php
/**
 * Created by Jesse.
 * Date: 2018/10/30
 * Time: 13:52
 */

namespace app\exception;


class BannerMissException extends BaseException
{
    public $code = 400;
    public $errorCode = 40000;
    public $msg = '请求的Banner不存在';
}