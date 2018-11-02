<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 10:33
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IdMustBePositiveInteger;
use app\api\exception\ProductException;

class Product
{
    /**
     * 获得最新商品
     * @url /product/recent
     * @http GET
     * @param ProductModel $product
     * @param Count $validate
     * @param int $count
     * @return mixed
     * @throws ProductException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecent(ProductModel $product, Count $validate, $count = 15)
    {
        $validate->goCheck();
        $result = $product->getMostRecent($count)->hidden(['summary']);
        if($result->isEmpty()) throw new ProductException;
        return $result;
    }

    /**
     * 获得分类的商品列表
     * @url /product/by_category?id=3
     * @http GET
     * @param ProductModel $product
     * @param IdMustBePositiveInteger $validate
     * @param $id
     * @return mixed
     * @throws ProductException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllInCategory(ProductModel $product, IdMustBePositiveInteger $validate, $id)
    {
        $validate->goCheck();
        $result = $product->getProductsByCategoryId($id)->hidden(['summary']);
        if($result->isEmpty()) throw new ProductException;
        return $result;
    }

    /**
     * 获得某一商品详情
     * @url /product/id
     * @http GET
     * @param ProductModel $product
     * @param IdMustBePositiveInteger $validate
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws ProductException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOne(ProductModel $product, IdMustBePositiveInteger $validate, $id)
    {
        $validate->goCheck();
        $result = $product->getProductById($id);
        if(!$result) throw new ProductException;
        return $result;
    }
}