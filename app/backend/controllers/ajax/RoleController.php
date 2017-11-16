<?php

/**
 * 通行证
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Backend\Controllers\Ajax;
use Marser\App\Backend\Controllers\BaseController;
use \Marser\App\Models\Role;
use \Marser\App\Models\Menu;
use \Marser\App\Models\RoleMenu;
use \Marser\App\Core\PhalBaseController;
use Phalcon\Http\Response;


class RoleController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 添加角色处理
     * @throws \Exception
     */
    public function addAction(){
        if(!$this -> request -> isAjax())
        {
            throw new \Exception('非法请求');
        }
        $title=$this->request->getPost('title','remove_xss');
        $description=$this->request->getPost('description','remove_xss');
        //添加验证规则
        $this -> validator -> add_rule('title', 'required', '角色名不能为空');
        $this -> validator -> add_rule('title', 'chinese_alpha_numeric_dash', '角色名不规范');
        $this -> validator -> add_rule('description', 'required', '角色描述不能为空');
        //截获验证异常
        if ($error = $this -> validator -> run(array(
            'title' => $title,
            'description' => $description,
        ))) {
            $error = array_values($error);
            $error = $error[0];
            $this->ajax_return($error['message'],self::CODE_ERROR);
            die();
        }
        //添加角色
        $role= new Role();
        $role->title=$title;
        $role->description=$description;
        $role->creater = $this -> user['id'];
        if ($role->save()){
            $this->write_log($this->request->getPost(),true,'添加角色-添加成功');
            $this->ajax_return('添加成功',self::CODE_SUCCESS);
        }else{
            $this->write_log($this->request->getPost(),false,'添加角色-添加失败');
            $this->ajax_return('添加失败',self::CODE_ERROR);
        }
    }
    /**
     * 管理员信息修改
     */
    public function editAction()
    {
        echo 'edit';
    }
    /**
     * 权限更新
     */
    public function changeAuthAction(){
        if (!$this->request->isAjax()) {
            $this->ajax_return('非法请求',self::CODE_ERROR);
        }
        $role_id = $this->request->getPost('id');

        $role = Role::findFirst($role_id);
        if(empty($role)){
            $this->ajax_return('该数据不存在',self::CODE_ERROR);
        }
        $rights_val = [
            'third'=>$this->request->getPost('third')?$this->request->getPost('third'):[],
            'second'=>$this->request->getPost('second')?$this->request->getPost('second'):[]
        ];
        
        $auth_rights = [];
        foreach ($rights_val['third'] as $key => $top) {
            if(!in_array($key, $auth_rights))
                $auth_rights[] = $key;
            foreach ($top as $key => $second) {
                if(!in_array($key, $auth_rights))
                    $auth_rights[] = $key;
                foreach ($second as $key => $third) {
                    if(!in_array($third, $auth_rights))
                        $auth_rights[] = $third;
                }
            }
        }

        foreach ($rights_val['second'] as $key => $top) {
            if(!in_array($key, $auth_rights))
                $auth_rights[] = $key;
            foreach ($top as $key => $second) {
                if(!in_array($second, $auth_rights))
                    $auth_rights[] = $second;
            }
        }
   
        $auth_num = empty($auth_rights)?0:Menu::count(
            array(
                'conditions' => 'id IN ({menu_ids:array})',
                'bind' => array(
                    'menu_ids' => $auth_rights
        )));

        if($auth_num != count($auth_rights)){
            $this->ajax_return('有权限被删除请刷新页面再试',self::CODE_ERROR);
        }
        else{
            //删除原来的权限
            RoleMenu::find(
            array(
                'conditions' => 'role_id = :role_id:',
                'bind' => array(
                    'role_id' => $role_id,
                )))->delete();
            $error_ids = [];
            foreach ($auth_rights as $key => $auth) {
                $role_menu = new RoleMenu();
                $role_menu->role_id = $role_id;
                $role_menu->menu_id = $auth;
                $role_menu->status = 1;
                $role_menu->creater = $this -> user['id'];
                if (!$role_menu->save()) {
                    //失败，记录失败信息
                    $error_ids[] = $auth;
                }
            }
           //删除缓存信息
            $this->get_repository('Admin')->del_role_auth($role_id);
            $this->get_repository('Admin')->del_menu_auth($role_id);
            if(empty($error_ids)){
                $this->write_log([$role_id],true,'权限更新-权限更新成功');
                $this->ajax_return('更新成功',self::CODE_SUCCESS);
            }else{
                $error_msg = implode(',',$error_ids);
                $this->write_log([$role_id],false,'服务器繁忙！'.$error_msg.'未成功赋权限');
                $this->ajax_return('权限更新-服务器繁忙！'.$error_msg.'未成功赋权限',self::CODE_ERROR);
            }
        }
        $menu_role_mid = RoleMenu::find(
            array(
                'conditions' => 'role_id = :role_id:',
                'bind' => array(
                    'role_id' => $role_id,
                ),));
        if (!empty($menu_role_mid->toArray())) {
            //删除用户之前的权限信息
            $menu_role_mid->delete();
        }
        $error_id = array();
        foreach ($rights as $k => $v) {

            $menu_parent_id = Menu::findFirst($v);
            if (!$menu_parent_id) {
                //未查到menu表里有当前信息，跳过执行下次
                continue;
            }
            //判断是否为顶级菜单（顶级不添加）
            $m_path = explode('/', $menu_parent_id->toArray()['path']);
            array_pop($m_path);
            array_shift($m_path);
            $path_count = count($m_path);
            if ($path_count <= 1) {
                //顶级菜单，跳过执行下次
                continue;
            }
            array_shift($m_path);
            array_push($m_path, $v);


            //判断role_menu表是否有当前数据，有添加，无不添加；
            foreach ($m_path as $key => $value) {
                $is_role_menu = RoleMenu::find(
                    array(
                        'conditions' => 'role_id = :role_id: AND menu_id = :menu_id:',
                        'bind' => array(
                            'role_id' => $role_id,
                            'menu_id' => $value,
                        ),)
                );
                if (!empty($is_role_menu->toArray())) {
                    continue;
                }

                //添加用户新的权限信息数据
                $role_menu = new RoleMenu();
                $role_menu->role_id = $role_id;
                $role_menu->menu_id = $value;
                $role_menu->status = 1;
                $role_menu->creater = $this -> user['id'];
                if (!$role_menu->save()) {
                    //失败，记录失败信息
                    $error_id[$k] = $rights_val[$v];
                }
            }
        }
        //删除缓存信息
        $this->get_repository('Admin')->del_role_auth($role_id);
        $this->get_repository('Admin')->del_menu_auth($role_id);

        if(empty($error_id)){
            $this->write_log($role_menu->toArray(),true,'权限更新成功');
            $this->ajax_return('更新成功',self::CODE_SUCCESS);
        }else{
            $error_msg = implode(',',$error_id);
            $this->write_log($role_menu->toArray(),false,'服务器繁忙！'.$error_msg.'未成功赋权限');
            $this->ajax_return('服务器繁忙！'.$error_msg.'未成功赋权限',self::CODE_ERROR);
        }
    }

    public function del(){
        $role_id=$this->request->get('id');
        $role=Role::findFirst($role_id);
        if (!$role==false){
            if ($role->delete()===false){
                $this->flashSession->error('删除失败!');
            }else{
                $this->flashSession->success('删除成功!');
            }
        }
        return $this->redirect();
    }

}