<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 21:02
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
}