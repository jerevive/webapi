<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 11:44
 */

namespace app\api\validate;


class OrderPlace extends BaseValidate
{

    protected $rule = [
        'products' => 'require|checkProducts'
    ];

    protected function checkProducts()
    {
        
    }
}