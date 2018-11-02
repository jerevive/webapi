<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 16:30
 */

namespace app\api\service;


use app\api\enum\Enum;
use app\api\exception\ForbiddenException;
use app\api\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Token
{
    /**
     * 生成Token值
     * @param $length
     * @return string
     */
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

    /**
     * 获得Token中任意一个变量
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public function getTokenVar($key)
    {
        /* get token */
        $token = Request::header('token');
        /* get cached value by token */
        $vars = Cache::get($token);
        if(!$vars) throw new TokenException();

        if(!is_array($vars))
        {
            $vars = json_decode($vars, true);
            if(array_key_exists($key, $vars))
            {
                return $vars[$key];
            }else
            {
                throw new Exception('尝试获得的Token变量并不存在');
            }
        }
    }

    /**
     * 获得Token中Uid
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public function getCurrentUID()
    {
        return $this->getTokenVar('uid');
    }

    /**
     * 用户和 管理员共有的权限
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     */
    public function needPrimaryScope()
    {
        $token = $this->getTokenVar('scope');
        if($token)
        {
            if($token >= (new Enum)->user)
            {
                return true;
            }else
            {
                throw new ForbiddenException;
            }
        }else
        {
            throw new TokenException;
        }
    }

    /**
     * 用户特有权限
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     */
    public function needExclusiveScope()
    {
        $token = $this->getTokenVar('scope');
        if($token)
        {
            if($token == (new Enum)->user)
            {
                return true;
            }else
            {
                throw new ForbiddenException;
            }
        }else
        {
            throw new TokenException;
        }
    }
}