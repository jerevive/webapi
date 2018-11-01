<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 11:43
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;

class Category
{
    /**
     * 获得所有分类
     * @return CategoryModel[]|false
     * @throws \think\Exception\DbException
     */
    public function getCategories()
    {
        $categories = CategoryModel::all([], 'topicImg');
        if($categories->isEmpty()) throw new CategoryException;
        return $categories;
    }
}