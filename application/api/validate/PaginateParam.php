<?php
/**
 * Created by Jesse.
 * Date: 2018/11/5
 * Time: 16:21
 */

namespace app\api\validate;


class PaginateParam extends BaseValidate
{
    protected $rule = [
        'page' => 'IsMustBePositiveInt',
        'count' => 'IsMustBePositiveInt',
    ];

    protected $message = [
        'page' => '当前页数必须是正整数',
        'count' => '分页数必须是正整数'
    ];
}