<?php
/**
 * Created by Jesse.
 * Date: 2018/11/5
 * Time: 14:09
 */

namespace app\api\service;

use app\api\enum\OrderStatusEnum;
use app\api\model\Product;
use think\Db;
use think\facade\Env;
use app\api\model\Order as OrderModel;
use think\facade\Log;

require_once Env::get('extend_path').'/wxpay/WxPay.Notify.php';
class WxNotify extends \WxPayNotify
{

    public function NotifyProcess($objData, $config, &$msg)
    {
        /**
         * <xml>
        <return_code><![CDATA[SUCCESS]]></return_code>
        <return_msg><![CDATA[OK]]></return_msg>
        <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
        <mch_id><![CDATA[10000100]]></mch_id>
        <nonce_str><![CDATA[IITRi8Iabbblz1Jc]]></nonce_str>
        <openid><![CDATA[oUpF8uMuAJO_M2pxb1Q9zNjWeS6o]]></openid>
        <sign><![CDATA[7921E432F65EB8ED0CE9755F0E86D72F]]></sign>
        <result_code><![CDATA[SUCCESS]]></result_code>
        <prepay_id><![CDATA[wx201411101639507cbf6ffd8b0779950874]]></prepay_id>
        <trade_type><![CDATA[JSAPI]]></trade_type>
        </xml>
         */
        if($objData['result_code'] == 'SUCCESS')
        {
            $prepay_id = $objData['prepay_id'];

            Db::startTrans();
            try
            {
                $order = OrderModel::where('prepay_id', 'eq', $prepay_id)->find();

                if ($order->status == 1)
                {
                    /* 库存量的检测 */
                    $stockStatus = (new Order)->checkOrderStock($order->id);
                    if ($stockStatus['pass'] == true) {
                        /* 修改支付状态为支付成功 */
                        $this->updatePayStatus($order->id, true);

                        /* 减库存 */
                        $this->reduceStock($stockStatus);
                    } else {
                        /* 修改支付状态为已支付缺货 */
                        $this->updatePayStatus($order->id, false);
                    }
                }

                Db::commit();

                return true;
            }catch (\Exception $e)
            {
                Db::rollback();
                Log::write($e);
                return false;
            }
        }else
        {
            return true;
        }
    }

    private function updatePayStatus($orderId, $success)
    {
        $status = $success ? OrderStatusEnum::IS_PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', 'eq', $orderId)->update(['status' => $status]);
    }

    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $singlePStatus)
        {
            Product::where('id', 'eq', $singlePStatus['id'])->setDec('stock', $singlePStatus['count']);
        }
    }
}