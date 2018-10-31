<?php

namespace app\api\controller\v1;

use think\Controller;
use app\api\validate\IdCollection;
use app\api\model\Theme as ThemeModel;
use app\exception\ThemeException;
use think\facade\Request;

class Theme extends Controller
{
    /**
     * 获得一组专题
     * @url /theme?id=1,2,3
     * @http get
     * @param ThemeModel $model
     * @param IdCollection $validate
     * @return array|\PDOStatement|string|\think\Collection
     * @throws ThemeException
     * @throws \app\exception\ParameterException
     */
    public function getSimpleList(ThemeModel $model, IdCollection $validate)
    {
        $validate->goCheck();
        $result = $model->getItemsByIds(Request::param('ids'));
        if($result->isEmpty()) throw new ThemeException;
        return $result;
    }

}
