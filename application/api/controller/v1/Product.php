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
     * @throws \app\exception\ParameterException
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
     * @throws \app\exception\ParameterException
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
}