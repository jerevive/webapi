<?php
/**
 * Created by Jesse.
 * Date: 2018/11/2
 * Time: 16:35
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function orderProduct()
    {
        return $this->hasMany('OrderProduct', 'order_id', 'id');
    }

    public function getSummaryByUser($uid, $page, $size)
    {
        return self::where('user_id', 'eq', $uid)
            ->order('create_time', 'desc')
            ->paginate($size, true, ['page' => $page]);
    }
}