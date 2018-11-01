<?php

namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = ['id', 'delete_time', 'update_time'];

    /**
     * 栏位下的Banner
     * @return \think\model\relation\HasMany
     */
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    /**
     * 轮播详情
     * @param $id
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBannerById($id)
    {
        return self::with(['items', 'items'=>['image']])->find($id);
    }

}
