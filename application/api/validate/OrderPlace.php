<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 11:44
 */

namespace app\api\validate;


use app\api\exception\ParameterException;

class OrderPlace extends BaseValidate
{

    protected $rule = [
        'products' => 'require|checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|IsMustBePositiveInt',
        'count' => 'require|IsMustBePositiveInt',
    ];

    protected function checkProducts($values)
    {
        if(empty($values))
        {
            throw new ParameterException(['msg' => '商品参数不能为空']);
        }

        if(!is_array($values))
        {
            throw new ParameterException(['msg' => '商品参数数据结构不合法']);
        }

        foreach($values as $value)
        {
            $this->checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }
    }
}