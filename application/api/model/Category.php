<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 11:44
 */

namespace app\api\model;


class Category extends BaseModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = ['create_time', 'update_time', 'delete_time', 'topic_img_id'];

    /**
     * 反相关联image表
     * @return \think\model\relation\BelongsTo
     */
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }
}