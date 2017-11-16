<?php

/**
 * 小程序登录控制器
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Frontend\Controllers\Sp_api;

use Marser\App\Frontend\Controllers\Sp_api\SpBaseController;
use Marser\App\Library\JwtAuth;

class UserController extends SpBaseController
{
    /**
     * 构造方法
     */
    public function onConstruct()
    {
        parent::jwtAuth();
    }

    /**
     * 用户登录
     * 验证账号密码，成功返回token信息
     */
    public function infoAction()
    {
        $this->ajax_return('success',self::CODE_SUCCESS,['email'=>$this->userInfo->email,'username'=>$this->userInfo->username,'id'=>$this->userInfo->id]);
    }
}
