<?php

namespace app\api\model;


class Theme extends BaseModel
{

    /**
     * 隐藏字段
     * @var array
     */
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

    /**
     * 获得专题简述信息
     * @param $ids
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getItemsByIds($ids)
    {
        return self::with(['topicImg', 'headImg'])->select($ids);
    }

    /**
     * 专题产品关联
     * @return \think\model\relation\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    /**
     * 专题详情页面
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getThemeWithProducts($id)
    {
        return self::with(['products', 'topicImg', 'headImg'])->find($id);
    }
}
