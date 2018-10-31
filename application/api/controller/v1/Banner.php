<?php
/**
 * Created by Jesse.
 * Date: 2018/10/29
 * Time: 21:24
 */

namespace app\api\controller\v1;

use think\Controller;
use app\api\validate\IdMustBePositiveInteger;
use app\api\model\Banner as BannerModel;
use think\facade\Request;
use app\exception\BannerMissException;

class Banner extends Controller
{
    /**
     * 获得Banner信息
     * @url /banner/id
     * @id Banner Id
     * @http GET
     * @param IdMustBePositiveInteger $validate
     * @param BannerModel $model
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws BannerMissException
     * @throws \app\exception\ParameterException
     */
    public function getBanner(IdMustBePositiveInteger $validate, BannerModel $model)
    {
        $validate->goCheck();
        $result = $model->getBannerById(Request::param('id'));
        if(!$result) throw new BannerMissException;
        return $result;
    }

}