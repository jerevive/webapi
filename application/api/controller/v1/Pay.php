<?php
/**
 * Created by Jesse.
 * Date: 2018/11/3
 * Time: 13:37
 */

namespace app\api\controller\v1;


use app\api\validate\IdMustBePositiveInteger;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use think\facade\Env;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    public function getPreOrder(IdMustBePositiveInteger $validate, PayService $pay, $id)
    {
        $validate->goCheck();
        return $pay->setOrderId($id)->pay();

    }

    public function receiveNotify(WxNotify $wxNotify)
    {
        require_once Env::get('extend_path').'/wxpay/WxPay.Notify.php';
        $wxConfig = new \WxPayConfig;
        $wxNotify->Handle($wxConfig);
    }
}