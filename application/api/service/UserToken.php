<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 14:06
 */

namespace app\api\service;


use app\api\Enum;
use app\api\model\User;
use app\api\exception\TokenException;
use app\api\exception\WxException;

class UserToken extends Token
{
    protected $code;
    protected $wxAppId;
    protected $wxSecret;
    protected $loginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxSecret = config('wx.app_secret');
        $this->loginUrl = sprintf(config('wx.login_url'), $this->wxAppId, $this->wxSecret, $this->code);
    }

    public static function instance($code)
    {
        return new static($code);
    }

    /**
     * 请求Wx.login接口
     * @throws TokenException
     * @throws WxException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get()
    {
        /* 请求小程序的openid */
        $result = curl_get($this->loginUrl);
        $wxResult = json_decode($result, true);
        if(array_key_exists('errcode', $wxResult))
        {
            $this->processWxError($wxResult);
        }else
        {
            // 生成令牌
            return $this->grantToken($wxResult);
        }
    }

    /**
     * 生成Token
     * @param $wxResult
     * @return string
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];

        /* find user */
        $user = User::where('openid', $openid)->find();
        if ($user) {
            $uid = $user->id;
        } else
        {
            /* create user */
            $uid = User::create(['openid' => $openid]);
        }

        /* cache data  */
        $cacheValue = $this->prepareCacheValue($wxResult, $uid);

        /* return token */
        return $this->cacheValue($cacheValue);

    }

    /**
     * 缓存Token
     * @param $cacheValue
     * @return string
     * @throws TokenException
     */
    private function cacheValue($cacheValue)
    {
        /* key => value */
        $token = $this->generateToken(32);
        $value = json_encode($cacheValue);
        /* token expire */
        $expire_in = config('setting.token_expire_in');
        /* cache token */
        $result = cache($token, $value, $expire_in);
        if(!$result)
        {
            throw new TokenException(['msg' => '服务器缓存异常', 'errorCode' => 10005]);
        }
        return $token;
    }

    /**
     * 缓存的数据
     * @param Enum $enum
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCacheValue($wxResult, $uid)
    {
        /* openid session_key */
        $cacheValue = $wxResult;
        /* user_id */
        $cacheValue['uid'] = $uid;
        /* scope */
        $cacheValue['scope'] = 15;

        return $cacheValue;
    }

    /**
     * 请求Wx.login 抛出异常
     * @param $wxResult
     * @throws WxException
     */
    private function processWxError($wxResult)
    {
        throw new WxException(['msg' => $wxResult['errmsg'], 'errorCode' => $wxResult['errcode']]);
    }
}