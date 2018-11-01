<?php

namespace app\api\model;


class Product extends BaseModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = ['delete_time', 'category_id', 'from', 'create_time', 'update_time', 'pivot'];

    /**
     * 修改图片地址
     * @param $value
     * @param $data
     * @return string
     */
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    /**
     * 获得最新商品
     * @param int $count
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMostRecent($count = 15)
    {
        return $this->limit($count)->order('create_time', 'desc')->select();
    }

    /**
     * 根据分类ID获得商品信息
     * @param $cat_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProductsByCategoryId($cat_id)
    {
        return $this->where('category_id', 'eq', $cat_id)->select();
    }

    /**
     * 商品详情表
     * @return \think\model\relation\HasMany
     */
    public function images()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    /**
     * 商品参数表
     * @return \think\model\relation\HasMany
     */
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    /**
     * 商品详细描述
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProductById($id)
    {
        return self::with(['images' => function($query){
            $query->with(['imgUrl'])->order('order', 'asc');
        }, 'properties'])->find($id);
    }
}
