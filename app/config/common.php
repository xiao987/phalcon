<?php
/**
 * 节本片子
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

return new \Phalcon\Config([

    /**
     * 微信授权认证配置
     */
    'wechat_auth' => [
        'app_id' => '',
        'secret' => '',
    ],

    /**
     * 签名信息配置信息
     */
    'api_sign' => [
        'key' => '',
        'status' => true,
        'expire_time' => 120,
    ],

    /**
     * 七牛上传配置信息
     */
    'qiniu' => [
        'accessKey' => '',
        'secretKey' => '',
        'bucket' => '',
        'url' => '',
    ],
    /**
     * 微信支付配置信息
     */
    'wechat_pay' => [
        'app' => [
            'app_id' => '',
            'app_secret' => '',
            'mch_id' => '',
            'md5_key' => '',
            'cert_pem' => '',
            'key_pem' => '',
        ],
        'web' => [
            'app_id' => '',
            'app_secret' => '',
            'mch_id' => '',
            'md5_key' => '',
            'cert_pem' => BASE_PATH.'/resource/cert/wechat/apiclient_cert.pem',
            'key_pem' => BASE_PATH.'/resource/cert/wechat/apiclient_key.pem',
        ]
    ],
    /**
     * JwtAuth token授权配置
     */
    'jwt_auth'=>[
        'type'=>'HS256',
        'key'=>'zgc@2017#ksx',
        'privete'=>BASE_PATH.'/resource/cert/jwtauth/id_ras',
        'public'=>BASE_PATH.'/resource/cert/jwtauth/id_ras.pub',
    ],
]);