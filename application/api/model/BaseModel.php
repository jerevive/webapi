<?php
/**
 * Created by Jesse.
 * Date: 2018/10/31
 * Time: 17:13
 */

namespace app\api\model;


use think\Model;
use think\facade\Config;

class BaseModel extends Model
{
    protected function prefixImgUrl($value, $data)
    {
        $finalValue = $value;
        if($data['from'] == 1)
        {
            $finalValue = Config::get('setting.imgPrefix').$value;
        }
        return $finalValue;
    }
}