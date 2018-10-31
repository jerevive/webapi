<?php

namespace app\api\model;


class Theme extends BaseModel
{

    protected $hidden = ['id', 'topic_img_id', 'delete_time', 'head_img_id', 'update_time'];

    /**
     * 专题图片
     * @return \think\model\relation\BelongsTo
     */
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    /**
     * 专题列表页的banner图
     * @return mixed
     */
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public function getItemsByIds($ids)
    {
        return self::with(['topicImg', 'headImg'])->select($ids);
    }
}
