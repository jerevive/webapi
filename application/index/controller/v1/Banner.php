<?php
/**
 * Created by Jesse.
 * Date: 2018/10/29
 * Time: 21:24
 */

namespace app\index\controller\v1;

use think\Controller;
use app\facade\IdValidate;
use app\facade\BannerModel;
use think\facade\Request;
use app\exception\BannerMissException;

class Banner extends Controller
{
    public function getBanner()
    {
        IdValidate::goCheck();
        $result = BannerModel::getBannerById(Request::param('id'));
        if(!$result)
        {
            throw new BannerMissException;
        }
    }

}