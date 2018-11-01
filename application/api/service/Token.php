<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 16:30
 */

namespace app\api\service;


class Token
{
    public function generateToken($length)
    {
        /* random char */
        $chars = getRandChar($length);

        /* timestamp */
        $timestamp = $_SERVER['REQUEST_TIME'];

        /* salt */
        $salt = config('secure.token_salt');

        return md5($chars. $timestamp. $salt);

    }
}