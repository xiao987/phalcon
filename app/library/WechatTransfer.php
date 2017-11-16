<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午5:23
 */

namespace App\Library;

use Payment\Common\PayException;
use Payment\Client\Transfer;
use App\Library\WechatPay;


class WechatTransfer
{

    /**
     * 微信企业转账
     * @param array $data
     * @return bool|mixed
     */
    public static function pay($data = array())
    {
        $data = [
            'trans_no' => $data['order_no'],
            'openid' => $data['openid'],
            'check_name' => 'NO_CHECK',// NO_CHECK：不校验真实姓名  FORCE_CHECK：强校验真实姓名   OPTION_CHECK：针对已实名认证的用户才校验真实姓名
            'payer_real_name' => '',
            'amount' => $data['amount'],
            'desc' => $data['title'],
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
        ];
        try {
            $ret = Transfer::run('wx_transfer', WechatPay::config(), $data);
            return !empty($ret['is_success']) ? true : false;
        } catch (PayException $e) {
            return false;
        }
    }


}
