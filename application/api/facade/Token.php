<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 10:52
 */

namespace app\api\facade;


use think\Facade;

/**
 * @see \app\api\service\Token
 * @method needPrimaryScope() static
 * @method needExclusiveScope() static
 * @method getCurrentUID() static
 * @method getTokenVar() static
 * Class Token
 * @package app\api\facade
 */
class Token extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\api\service\Token';
    }
}