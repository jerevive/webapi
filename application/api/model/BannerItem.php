<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = ['id', 'img_id', 'type', 'banner_id', 'update_time', 'delete_time'];

    /**
     * 反向关联image表
     * @return \think\model\relation\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
