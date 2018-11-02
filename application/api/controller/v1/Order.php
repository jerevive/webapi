<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 10:43
 */

namespace app\api\controller\v1;


use app\api\validate\OrderPlace;

class Order extends BaseController
{

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    public function placeOrder(OrderPlace $validate)
    {
        $validate->goCheck();
    }
}