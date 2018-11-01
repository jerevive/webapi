<?php

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IdCollection;
use app\api\validate\IdMustBePositiveInteger;
use app\exception\ThemeException;
use think\facade\Request;

class Theme
{
    /**
     * 专题列表简要信息
     * @url /theme?id=1,2,3
     * @http GET
     * @param ThemeModel $theme
     * @param IdCollection $validate
     * @return array|\PDOStatement|string|\think\Collection
     * @throws ThemeException
     * @throws \app\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSimpleList(ThemeModel $theme, IdCollection $validate)
    {
        $validate->goCheck();
        $result = $theme->getItemsByIds(Request::param('ids'));
        if($result->isEmpty()) throw new ThemeException;
        return $result;
    }

    /**
     * 专题列表详情
     * @url /theme/id
     * @http GET
     * @param ThemeModel $theme
     * @param IdMustBePositiveInteger $validate
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws ThemeException
     * @throws \app\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getComplexOne(ThemeModel $theme, IdMustBePositiveInteger $validate)
    {
        $validate->goCheck();
        $result = $theme->getThemeWithProducts(Request::param('id'));
        if(empty($result)) throw new ThemeException;
        return $result;
    }

}
