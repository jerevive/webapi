<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 10:43
 */

namespace app\api\controller\v1;


use app\api\validate\OrderPlace;
use app\api\service\Order as OrderService;
use app\api\facade\Token;
use app\api\validate\PaginateParam;
use think\facade\Request;
use app\api\model\Order as OrderModel;

class Order extends BaseController
{

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getSummaryByUser'],
    ];

    /**
     * 下订单处理
     * @param OrderPlace $validate
     * @param OrderService $orderService
     * @return array
     * @throws \app\api\exception\ParameterException
     */
    public function placeOrder(OrderPlace $validate, OrderService $orderService)
    {
        $validate->goCheck();
        $uid = Token::getCurrentUID();
        $uProducts = Request::post('products/a');
        $status = $orderService->place($uid, $uProducts);
        return $status;
    }

    /**
     * 分页获得订单摘要信息
     * @param OrderModel $order
     * @param PaginateParam $validate
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\api\exception\ParameterException
     */
    public function getSummaryByUser(OrderModel $order, PaginateParam $validate, $page = 1, $size = 15)
    {
        $validate->goCheck();
        $uid = Token::getCurrentUID();
        $orders = $order->getSummaryByUser($uid, $page, $size);
        if($orders->isEmpty())
        {
            return [
                'data' => [],
                'current_page' => $orders->currentPage()
            ];
        }

        $data = $orders->hidden(['snap_items', 'prepay_id', 'snap_address'])->toArray();
        return ['data' => $data, 'current_page' => $orders->currentPage()];
    }
}