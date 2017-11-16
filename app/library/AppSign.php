<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/17
 * Time: 下午2:41
 */
namespace App\Library;


use Phalcon\Di;

class AppSign
{


    /**
     * 验证签名数据、注入app类
     * @param $app
     * @return array
     */
    public static function check()
    {
        $api_config = Di::getDefault()->get("commonConfig")->api_sign;
        /** 获取签名配置信息 */
        $sign_key = $api_config->key;
        $sign_status = $api_config->status;
        $sign_expire = $api_config->expire_time;
        /** 获取post请求信息 */
        $data = Di::getDefault()->getRequest()->getPost();
        if(empty($data['sign']) || empty($data['timestamp'])){
            response_data(-1,'签名参数异常');
        }
        $sign_origin = $data['sign'];
        $timestamp = $data['timestamp'];
        unset($data['sign'], $data['timestamp']);
        $keys = array_keys($data);
        sort($keys);
        $signStr = '';
        foreach ($keys as $value) {
            if (is_array($data[$value]) && $data[$value]) {
                foreach ($data[$value] as $k => $v) {
                    $signStr .= $value . '[' . $k . ']' . '=' . $v . '&';
                }
            } else {
                $signStr .= $value . '=' . $data[$value] . '&';
            }
        }
        $signStr = $keys ? $signStr . 'appKey=' . $sign_key . '&timestamp=' . $timestamp : 'appKey=' . $sign_key . '&timestamp=' . $timestamp;
        $sign = strtoupper(md5($signStr));
        if ($sign_status) {
            if ($sign != $sign_origin) {
                response_data(-1,'签名信息错误');
            }
            if ($timestamp < time() - $sign_expire) {
                response_data(-1,'签名信息已过期');
            }
        }
    }

}
