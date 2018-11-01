<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 10:41
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'IsMustBePositiveInt|between:1,15'
    ];
}