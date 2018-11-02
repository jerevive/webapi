<?php
/**
 * Created by Jesse.
 * Date: 2018/10/29
 * Time: 21:24
 */

namespace app\api\controller\v1;


use app\api\validate\IdMustBePositiveInteger;
use app\api\model\Banner as BannerModel;
use think\facade\Request;
use app\api\exception\BannerMissException;

class Banner
{

    /**
     * 获得Banner信息
     * @url /banner/id
     * @id Banner Id
     * @param IdMustBePositiveInteger $validate
     * @param BannerModel $banner
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws BannerMissException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBanner(IdMustBePositiveInteger $validate, BannerModel $banner)
    {
        $validate->goCheck();
        $result = $banner->getBannerById(Request::param('id'));
        if(!$result) throw new BannerMissException;
        return $result;
    }

}