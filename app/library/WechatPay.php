<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/19
 * Time: 下午2:22
 */

namespace App\Library;

use Phalcon\Di;
use Payment\Common\PayException;
use Payment\Client\Charge;

class WechatPay
{

    private static $pay_type;

    /**
     * 配置信息
     * @param array $data
     * @return array
     */
    public static function config($data = array())
    {
        $config = Di::getDefault()->get("commonConfig")->wechat_pay;
        $config = in_array(self::$pay_type, ['wx_app']) ? $config->app : $config->web;

        return [
            'use_sandbox' => !empty($config['use_sandbox']) ? $config['use_sandbox'] : false,// 是否使用 微信支付仿真测试系统
            'app_id' => $config['app_id'],  // 公众账号ID
            'mch_id' => $config['mch_id'],// 商户id
            'md5_key' => $config['md5_key'],// md5 秘钥
            'app_cert_pem' => $config['cert_pem'],//证书
            'app_key_pem' => $config['key_pem'],//密钥
            'sign_type' => 'MD5',//签名类型
            'limit_pay' => [],// no_credit 指定不能使用信用卡支付   不传入，则均可使用
            'fee_type' => 'CNY',// 货币类型  当前仅支持该字段
            'notify_url' => !empty($data['notify_url']) ? $data['notify_url'] : 'http://baidu.com',
            'redirect_url' => !empty($data['return_url']) ? $data['return_url'] : '',// 如果是h5支付，可以设置该值，返回到指定页面
            'return_raw' => false,// 在处理回调时，是否直接返回原始数据，默认为true
        ];
    }

    /**
     * 设置微信支付的类型【APP支付、公众号支付、扫码支付、刷卡支付、小程序支付、H5支付】
     * @param string $type
     * @return WechatPay
     */
    public static function type($type = 'app')
    {
        if (!in_array($type, ['app', 'pub', 'qr', 'bar', 'lite', 'wap'])) {
            output_data(-1, '微信支付类型错误');
        }
        self::$pay_type = 'wx_' . ($type ? $type : 'app');
        return new WechatPay();
    }

    /**
     * 微信支付[ title、order_no、amount、param、notify_url、redirect_url、openid ]
     * @param array $data
     * @return bool|mixed
     */
    public function pay($data = array())
    {
        $config = self::config($data);
        /** 重组支付信息参数 */
        $payData = [
            'body' => $data['title'],//付款内容
            'subject' => $data['title'],//付款标题
            'order_no' => $data['order_no'],//订单号
            'timeout_express' => time() + 1800,//超时时间
            'amount' => $data['amount'],//金额（元）
            'client_ip' => $_SERVER['REMOTE_ADDR'],//客户端ip
            'return_param' => json_encode($data['param']),
            'terminal_id' => 'WEB',//终端ID，APP、PC、公众号、H5需要传入WEB
        ];

        /** 公众号支付、小程序支付都需要传入openid */
        if (in_array(self::$pay_type, ['wx_pub', 'wx_lite'])) {
            $payData['openid'] = $data['openid'];
        }

        /** 开始执行 */
        try {
            $str = Charge::run(self::$pay_type, $config, $payData);
            return json_decode($str,true);
        } catch (PayException $e) {
            return false;
        }
    }


}
