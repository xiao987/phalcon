<?php

/**
 * 后台基类控制器
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Backend\Controllers;

use Marser\App\Models\Menu;
use Marser\App\Models\Role;
use Marser\App\Models\Adminer_log;
use \Marser\App\Core\PhalBaseController,
    \Marser\App\Backend\Repositories\RepositoryFactory;

class BaseController extends PhalBaseController{
    protected $user=null;
    private $_allow_controllers = array('dashboard','index','passport','account');
    private $_super_admin_id = 1;
    public function initialize(){
        parent::initialize();

        $this -> login_check();

        $this -> set_common_vars();
        //角色访问权限控制
        $this->check_access();
    }

    //访问权限控制
    protected function check_access()
    {
 
        $check_contronler_action = strtolower($this->dispatcher->getControllerName()."/".$this->dispatcher->getActionName());

        $authority_result = $this-> check_authority([$check_contronler_action],$this -> user['id'],$this -> user['role_id']);
        $is_access = false;

        if(isset($authority_result[$check_contronler_action]) && $authority_result[$check_contronler_action]==true){
            $is_access = true;
        }
        else{
            $is_access = false;
        }
        
        if($is_access===false){
            //普通请求输出无权限提示

            if(!$this -> request -> isAjax()){
                $this -> flashSession -> success('没有权限');
                //$this -> redirect("dashboard/index");
                $this -> dispatcher -> forward(array(
                    'controller'    =>    'dashboard',
                    'action'        =>    'index'
                ));
            }
            else{
                //ajax请求输出无权限提示
                $this->ajax_return("没有权限",self::CODE_ERROR,array(),self::ERROR_CODE_NO_AUTH);
            }
        }
        if($is_access && !$this -> request -> isAjax() ) {
            $this->set_admin_menu($this -> user['id'],$this -> user['role_id']);
        }
        return $is_access;
    }

    /**
     * 检查用户是否有操作权限
     * @param user_id
     * @param role_id
     * @param authoritys
    **/

    protected function check_authority($authority=array(),$user_id=null,$role_id=null){
        $result_auths = [];
        if(empty($authority)){
            return $result_auths;
        }
        if($user_id==null)
        {
            $user_id = $this->user['id'];
        }
        if($role_id==null)
        {
            $role_id = $this->user['role_id'];
        }
        if($user_id != $this->_super_admin_id){

            $auth_list = $this -> get_repository('admin')->get_role_list_by_role_id($role_id);
            foreach ($authority as $value) {
                $old_value = $value;
                $value = strtolower($value);
                list($controller,$action) = explode('/', $value);
                if(in_array($controller, $this->_allow_controllers)){
                    $result_auths[$old_value] = true;
                }
                elseif(isset($auth_list[$value]) && $auth_list[$value]==true){
                    $result_auths[$old_value] = true;
                }
            }
        }
        else{

            foreach ($authority as $value) {
                $result_auths[$value] = true;
            }
        }
        return $result_auths;

    }
    /**
     * 后台菜单过滤
     * @param user_id
     * @param role_id
     */
    private function set_admin_menu($user_id,$role_id)
    {

        if(empty($user_id) || empty($role_id)){
            $menu_list = [];
        }
        else{
            $menu_list = $this -> get_repository('admin')->get_menu_list_by_role_id($role_id,($user_id==$this->_super_admin_id));
        }

        $this -> view -> setVars(array(
            'topList' => $menu_list,
        ));
    }

    /**
     * 设置模块公共变量
     */
    public function set_common_vars(){
        $this -> view -> setVars(array(
            'title' => $this -> systemConfig -> app -> app_name,
            'userinfo' => $this->user,
            'assetsVersion' => $this -> version_config->version,
        ));
    }

    /**
     * 登录检测处理
     * @return bool
     */
    protected function login_check(){
        if(!$this -> get_repository('admin') -> login_check()){

            return $this -> redirect("passport/index");
        }
        $this->user = $this -> session -> get('adminer');
        return true;
    }

    /**
     * 获取业务对象
     * @param $repositoryName
     * @return object
     * @throws \Exception
     */
    protected function get_repository($repositoryName){
        return RepositoryFactory::get_repository($repositoryName);
    }

    /**
     * 页面跳转
     * @param null $url
     */
    protected function redirect($url=NULL){
        empty($url) && $url = $this -> request -> getHeader('HTTP_REFERER');
        $this -> response -> redirect($url);
    }
    /**
     * 记录日志
     * @param array $content 
     * @param int $status 
     * @param varchar remark
     * @return mixed
     */
    protected function write_log($content, $status, $remark) {
        $adminer_log = new Adminer_log();
        $adminer_log -> admin_id = $this -> user['id'];
        $adminer_log -> username = $this -> user['username'];
        $adminer_log -> query = json_encode($content);
        $adminer_log -> url = $this -> request ->getURI();
        $adminer_log -> creater = $this -> user['id'];
        $adminer_log -> remark = $remark;
        $adminer_log -> status = $status? Adminer_log :: OPERATION_YES:Adminer_log :: OPERATION_NO;
        return $adminer_log -> save();
    }
}