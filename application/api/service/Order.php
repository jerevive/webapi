<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 12:34
 */

namespace app\api\service;


use app\api\exception\OrderException;
use app\api\exception\UserException;
use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

class Order
{
    // 商品参数列表
    protected $oProducts;

    // 商品参数列表详细信息
    protected $products;

    protected $uid;

    public function place($uid, $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->uid = $uid;
        $this->products = $this->getProductsByOrder($oProducts);

        /* 检测库存 */
        $status = $this->getOrderStatus();
        if(!$status['pass'])
        {
            $status['order_id'] = -1;
            return $status;
        }

        /* 创建订单 */
        $snap = $this->snapOrder($status);
        $order = $this->createOrder($snap);
        $order['pass'] = true;
        return $order;
    }

    /**
     * 创建订单
     * @param $snap
     * @return array
     * @throws Exception
     */
    private function createOrder($snap)
    {
        Db::startTrans();
        try {
            /* 订单主表 */
            $order = OrderModel::create([
                'order_no' => $this->makeOrderNo(),
                'user_id' => $this->uid,
                'total_price' => $snap['orderPrice'],
                'snap_name' => $snap['snapName'],
                'snap_img' => $snap['snapImg'],
                'total_count' => $snap['totalCount'],
                'snap_items' => json_encode($snap['pStatus']),
                'snap_address' => $snap['snapAddress']
            ]);



            /* 订单商品表 */
            $order->orderProduct()->saveAll($this->oProducts);

            Db::commit();

            return [
                'order_no' => $order->order_no,
                'order_id' => $order->id,
                'create_time' => OrderModel::get($order->id)->create_time
            ];
        }catch(Exception $e)
        {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 订单快照
     * @param $status
     * @throws UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => '',
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        if(count($this->products) > 0)
        {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    /**
     * 获得订单的用户地址
     * @return array
     * @throws UserException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getUserAddress()
    {
        $userAdd =  UserAddress::where('user_id', 'eq', $this->uid)->find();
        if(!$userAdd)
        {
            throw new UserException(['msg' => '用户收货地址不存在，下单失败', 'errorCode' => 60001]);
        }
        return $userAdd->toArray();
    }

    /**
     * 检测订单库存 对外接口
     * @param $orderId
     * @return array
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkOrderStock($orderId)
    {
        $this->oProducts = OrderProduct::where('order_id', 'eq', $orderId)->select();

        $this->products = $this->getProductsByOrder($this->oProducts);

        return $this->getOrderStatus();

    }

    /**
     * 订单数据及库存检测
     * @return array
     * @throws OrderException
     */
    private function getOrderStatus()
    {
        $status = ['pass' => true, 'orderPrice' => 0, 'pStatusArray' => [], 'totalCount' => 0];

        foreach($this->oProducts as $oProduct)
        {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if(!$pStatus['hasStock']) $status['pass'] = false;
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }

        return $status;
    }

    /**
     * 根据商品参数列表获得订单商品详情
     * @param $oProducts
     * @return mixed
     * @throws \think\Exception\DbException
     */
    private function getProductsByOrder($oProducts)
    {
        // 获得所有的商品ID
        $proIds = [];
        foreach ($oProducts as $product) {
            array_push($proIds, $product['product_id']);
        }

        // 根据ID查询商品
        return Product::all($proIds)->visible(['id', 'name', 'price', 'stock', 'main_img_url'])->toArray();
    }

    /**
     * 订单内商品详细数据
     * @param $oPid
     * @param $oCount
     * @param $products
     * @return array
     * @throws OrderException
     */
    private function getProductStatus($oPid, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'name' => '',
            'count' => 0,
            'hasStock' => false,
            'totalPrice' => 0
        ];

        /* 对应商品ID */
        for($i=0;$i<count($products);$i++)
        {
            if($oPid == $products[$i]['id'])
            {
                $pIndex = $i;
            }
        }

        if($pIndex == -1)
        {
            throw new OrderException(['msg' => 'ID为'.$oPid.'的商品不存在']);
        }else
        {
            $pStatus['id'] = $pIndex;
            $pStatus['name'] = $products[$pIndex]['name'];
            $pStatus['count'] = $oCount;
            $pStatus['hasStock'] = ($products[$pIndex]['stock'] - $oCount > 0) ? true : false;
            $pStatus['totalPrice'] = $oCount * $products[$pIndex]['price'];
        }

        return $pStatus;
    }

    /**
     * 订单编号
     * @return string
     */
    public function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }
}