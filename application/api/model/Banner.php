<?php

namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{

    protected $hidden = ['id', 'delete_time', 'update_time'];

    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    public function getBannerById($id)
    {
        return self::with(['items', 'items'=>['image']])->find($id);
    }

}
