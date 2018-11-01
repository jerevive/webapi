<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 13:58
 */

namespace app\api\validate;


class Code extends BaseValidate
{

    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => 'code参数出错'
    ];
}