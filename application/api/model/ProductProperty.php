<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 17:59
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{

    /* 隐藏字段 */
    protected $hidden = ['delete_time', 'update_time', 'product_id'];
}