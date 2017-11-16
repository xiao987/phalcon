<?php

/**
 * 小程序基类控制器
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Frontend\Controllers\Sp_api;
use Marser\App\Library\AppSign;
use Marser\App\Library\WechatAuth;
use Marser\App\Library\JwtAuth;
use \Marser\App\Core\PhalBaseController;

class SpBaseController extends PhalBaseController
{
    public $userInfo;

    /**
     * 构造函数
     */
    public function initialize(){
        parent::initialize();
    }

    /**
     * 签名配置
     */
    public function appSign()
    {
        AppSign::check();
    }

    /**
     * 微信授权
     */
    public function wechatAuth()
    {
        $wechat = new WechatAuth();
        $info = $wechat->auth();
        if (empty($info->openid)) {
            $this->ajax_return('微信授权失败',self::CODE_ERROR);
        }
    }

    /**
     * token验证
     */
    public function checkToken()
    {
    }

    /**
     * jwt-auth校验
     */
    public function jwtAuth()
    {
        $token = !empty($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        if (!$token) {
            $this->ajax_return('token信息已失效，请重新登录',self::CODE_ERROR);
        }
        $info = JwtAuth::type()->decode($token);
        if (!$info) {
            $this->ajax_return('token信息已失效，请重新登录',self::CODE_ERROR);
        }
        $this->userInfo = $info;
    }

    /**
     * 接口404返回
     */
    public function route404Action()
    {
        $this->ajax_return('哎呀，你访问的接口已经飞走了',self::CODE_NO_FOUND);
    }

    /**
     * 接口500返回
     */
    public function route500Action()
    {
        $this->ajax_return('哎呀，服务器好像出现了错误',self::CODE_ERROR_INTERNAL);
    }
}
