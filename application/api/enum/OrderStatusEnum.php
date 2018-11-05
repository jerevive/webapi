<?php
/**
 * Created by Jesse.
 * Date: 2018/11/3
 * Time: 15:30
 */

namespace app\api\enum;


class OrderStatusEnum
{
    /* 未支付 */
    const UN_PAY = 1;

    /* 已支付 */
    const IS_PAID = 2;

    /* 已发货 */
    const DELIVERED = 3;

    /* 已支付未发货 (库存不足) */
    const PAID_BUT_OUT_OF = 4;
}