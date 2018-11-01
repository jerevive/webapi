<?php
/**
 * Created by Jesse.
 * Date: 2018/11/1
 * Time: 19:34
 */

namespace app\api\controller\v1;


use app\api\model\User as UserModel;
use app\api\validate\AddressNew;
use app\api\service\Token;
use app\exception\SuccessMessage;
use app\exception\UserException;
use think\facade\Request;

class Address
{
    /**
     * 新增或更新用户地址
     * @param AddressNew $validate
     * @param Token $service
     * @return SuccessMessage
     * @throws UserException
     * @throws \app\exception\ParameterException
     * @throws \think\Exception\DbException
     */
    public function createOrUpdateAddress(AddressNew $validate, Token $service)
    {
        $validate->goCheck();

        /* 根据Token 获得UID */
        $uid = $service->getCurrentUID();
        /* 判断用户是否存在 */
        $user = UserModel::get($uid);
        if(!$user) throw new UserException;

        /* 获得提交的数据 */
        $dataArr = $validate->getDataByRule(Request::param('post.'));

        /* 新增或更新用户地址 (模型关联) */
        $user->together('address')->save($dataArr);

        return json(new SuccessMessage, 201);
    }
}