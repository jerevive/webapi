<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 19:36
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{

    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isNotEmpty',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];

}