<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 14:04
 */

namespace app\api\model;


class User extends BaseModel
{

    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }
}