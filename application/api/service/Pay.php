<?php
/**
 * Created by Jesse.
 * Date: 2018/11/3
 * Time: 13:39
 */

namespace app\api\service;


use app\api\enum\OrderStatusEnum;
use app\api\exception\OrderException;
use app\api\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use think\facade\Env;
use think\facade\Log;

class Pay
{
    private $orderId;
    private $orderNo;


    /**
     * 微信支付主方法
     * @param Order $order
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function pay()
    {
        /* 订单验证 */
        $this->checkOrderValidate();

        /* 库存检测 */
        $status = (new Order)->checkOrderStock($this->orderId);

        /* 获得支付预订单 */
        return  $this->makeWxPreOrder($status['orderPrice']);
    }

    /**
     * 订单合法性验证
     * @return bool
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function checkOrderValidate()
    {
        /* 订单是否存在 */
        $order = OrderModel::where('id', 'eq', $this->orderId)->find();

        /* 订单是否属于当前用户 */
        if (!(new Token)->isValidateOperate($order->user_id)) {
            throw new  TokenException([
                'msg' => '订单与当前用户不符',
                'errorCode' => 10003
            ]);
        }

        /* 订单是否已被支付 */
        if ($order->status == OrderStatusEnum::IS_PAID) {
            throw new OrderException([
                'msg' => '订单已被支付',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }

        $this->orderNo = $order->order_no;

        return true;
    }

    /**
     * 生成微信支付预订单
     * @param $totalFree
     * @return bool
     * @throws TokenException
     */
    private function makeWxPreOrder($totalFree)
    {
        /* 获得下单用户openid */
        $openid = (new Token)->getTokenVar('openid');
        if(!$openid) throw new TokenException;

        /* 统一下单接口 */
        require_once Env::get('extend_path').'/wxpay/WxPay.Api.php';
        $wxOrderData = new \WxPayUnifiedOrder;
        /* 订单编号 */
        $wxOrderData->SetOut_trade_no($this->orderNo);
        /* 交易类型 */
        $wxOrderData->SetTrade_type('JSAPI');
        /* 交易金额 */
        $wxOrderData->SetTotal_fee($totalFree * 100);
        /* 商品描述 */
        $wxOrderData->SetBody('零售商贩');
        /* openid */
        $wxOrderData->SetOpenid($openid);
        /* 回调地址 */
        $wxOrderData->SetNotify_url('http://qq.com');

        return $this->getPaySignature($wxOrderData);

    }

    /**
     * 预订单创建成功后的处理
     * @param $wxOrderData
     * @return null
     * @throws \WxPayException
     */
    private function getPaySignature($wxOrderData)
    {

        require_once Env::get('extend_path').'/wxpay/WxPay.Config.php';
        $payConfig = new \WxPayConfig;
        $wxOrder = \WxPayApi::unifiedOrder($payConfig, $wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS')
        {
            Log::write($wxOrderData, 'error');
            Log::write('获得预支付订单失败', 'error');
        }

        /* 保存prepay_id */
        $this->recordPreOrder($wxOrder);
        return $this->sign($wxOrder);
    }


    /**
     * 保存perpay_id
     * @param $wxOrder
     */
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id', 'eq', $this->orderId)->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    private function sign($wxOrder)
    {
        require_once Env::get('extend_path').'/wxpay/WxPay.Data.php';
        require_once Env::get('extend_path').'/wxpay/WxPay.Config.php';

        $jsApiPayData = new \WxPayJsApiPay;
        $jsApiPayData->SetAppid(Env::get('APP_ID'));
        $str = md5(time().mt_rand(10,10000));
        $jsApiPayData->SetNonceStr($str);
        $jsApiPayData->SetPackage('prepay_id = '.$wxOrder['package']);
        $jsApiPayData->SetSignType('MD5');
        $jsApiPayData->SetTimeStamp((string)time());

        /* 生成签名 */
        $payConfig = new \WxPayConfig;
        $sign = $jsApiPayData->MakeSign($payConfig);

        /* 返回数据 */
        $rowValues = $jsApiPayData->GetValues();
        $rowValues['paySign'] = $sign;
        unset($rowValues['appId']);

        return $rowValues;
    }

    /**
     * 对外设置 order_id
     * @param $order_id
     * @return $this
     * @throws Exception
     */
    public function setOrderId($order_id)
    {
        if (empty($order_id)) throw new Exception('订单ID不能为NULL');
        $this->orderId = $order_id;
        return $this;
    }
}