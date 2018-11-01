<?php

namespace app\api\model;

use think\facade\Config;

class Image extends BaseModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = ['id', 'from', 'delete_time', 'update_time'];

    /**
     * 修改图片地址
     * @param $value
     * @param $data
     * @return string
     */
    public function getUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }
}
