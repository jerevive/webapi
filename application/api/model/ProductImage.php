<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 17:53
 */

namespace app\api\model;


class ProductImage extends BaseModel
{

    /* 隐藏字段 */
    protected $hidden = ['delete_time', 'img_id', 'product_id'];

    /**
     * 关联图片表
     * @return \think\model\relation\BelongsTo
     */
    public function imgUrl()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}