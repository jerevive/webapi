<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 12:34
 */

namespace app\api\service;


use app\api\exception\OrderException;
use app\api\model\Product;

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

    private function getOrderStatus()
    {
        $status = ['pass' => false, 'orderPrice' => 0, 'pStatusArray' => []];

        foreach($this->oProducts as $oProduct)
        {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            if(!$pStatus['hasStock']) $status['pass'] = false;
            $status['orderPrice'] += $pStatus['totalPrice'];
        }

        return $status;
    }

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
                $pIndex = $oPid;
            }
        }

        if($pIndex == -1) throw new OrderException(['msg' => 'ID为'.$oPid.'的商品不存在']);

        $pStatus['id'] = $pIndex;
        $pStatus['name'] = $products[$pIndex]['name'];
        $pStatus['count'] = $oCount;
        $pStatus['hasStock'] = ($products[$pIndex]['stock'] - $oCount > 0) ? true :false;
        $pStatus['totalPrice'] = $oCount * $products[$pIndex]['price'];

        return $pStatus;
    }
}