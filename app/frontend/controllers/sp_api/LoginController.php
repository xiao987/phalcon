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
use Marser\App\Models\Admin;
class LoginController extends SpBaseController
{


    /**
     * 管理员登录
     * 验证账号密码，成功返回token信息
     */
    public function UserSignInAction()
    {
        // $data = file_get_contents("php://input");
        // $data = json_decode($data,true);
        // if(!$data){
        //     $this->ajax_return('参数异常',self::CODE_ERROR);
        // }
        $data = $this->request->getPost();
        if(!$data){
            $this->ajax_return('参数异常',self::CODE_ERROR);
        }
        //验证参数
        $result = Admin::findFirst(['username = "'.$data['username'].'"']);
        if(!$result){
            $this->ajax_return('该账号不存在',self::CODE_ERROR);
        }

        // if(!passwordVerify($data['password'],$result->password)){
        //     $this->ajax_return('密码错误请重新核对密码',self::CODE_ERROR);
        // }
        $result->login_time = time();
        $result->save();
        if(isset($data->password)){
            unset($data->password);
        }
        $token = JwtAuth::type()->encode($result);
        $this->ajax_return('登录成功',self::CODE_SUCCESS,compact('token'));
    }

}
