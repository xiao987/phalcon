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
use \Marser\App\Models\Menu;
use Marser\App\Models\RoleMenu;
use Marser\App\Models\Admin;
use Phalcon\Http\Response;
use \Marser\App\Models\BaseModel;


class MenuController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 添加操作
     */
    public function addAction(){
        try{
            if(!$this -> request -> isAjax()){
                throw new \Exception('非法请求');
            }
            $id = intval($this -> request -> getPost('id', 'trim'));
            $name = $this -> request -> getPost('name', 'remove_xss');
            $url = $this -> request -> getPost('url', 'remove_xss');
            $parentid = intval($this -> request -> getPost('parentid', 'trim'));

            $sort = intval($this -> request -> getPost('sort', 'trim'));
            $model = $this -> request -> getPost('model', 'remove_xss');
            $controller = $this -> request -> getPost('controller', 'remove_xss');
            $action = $this -> request -> getPost('action', 'remove_xss');
            /** 添加验证规则 */
            $this -> validator -> add_rule('name', 'required', '请填写菜单名称');
            $this -> validator -> add_rule('url', 'required', '请填写菜单链接');
            $this -> validator -> add_rule('url', 'menu_url', '菜单链接不规范');
            $this -> validator -> add_rule('model', 'required', '请填写模块名');
            $this -> validator -> add_rule('model', 'menu_mvc', '模块名称不规范');
            $this -> validator -> add_rule('controller', 'required', '请填写控制器名');
            $this -> validator -> add_rule('controller', 'menu_mvc', '控制器名称不规范');
            $this -> validator -> add_rule('action', 'required', '请填写方法名');
            $this -> validator -> add_rule('action', 'menu_mvc', '方法名称不规范');
            $this -> validator -> add_rule('sort', 'int', '排序只能为数字');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'name' => $name,
                'url' => $url,
                'model' => $model,
                'controller' => $controller,
                'action' => $action,
                'sort' => $sort

            ))) {
                $error = array_values($error);
                $error = $error[0];
                $this->ajax_return($error['message'],self::CODE_ERROR);
            }

            //菜单数据入库
            if($id <= 0){
                //添加菜单
                $is_add = $this->menu_create(array(
                    'name' => $name,
                    'url' => $url,
                    'parent_id' => $parentid,
                    'sort' => $sort,
                    'model'=>$model,
                    'controller'=>$controller,
                    'action'=>$action,
                    'creater'=>$this->user['id']

                ));
                if($is_add>=0){
                    $this->write_log($this -> request -> getPost(),true,'菜单管理-添加成功');
                    //删除菜单缓存
                    $this -> get_repository('Menu') -> delete_menu_list_cache();
                    //获取超级管理员的角色信息
                    $admin = admin::findFirst(1);
                    $admin_role = $admin->toArray()['role_id'];
                    $this->get_repository('Admin')->del_menu_auth($admin_role);
                    $this->ajax_return('添加菜单成功',self::CODE_SUCCESS);
                }else{
                    $this->write_log($this -> request -> getPost(),false,'菜单管理-添加失败');
                    $this->ajax_return('添加菜单失败',self::CODE_SUCCESS);
                }
            }else{
                //更新菜单
                $is_update = $this->menu_update(array(
                    'name' => $name,
                    'url' => $url,
                    'parent_id' => $parentid,
                    'sort' => $sort,
                    'model'=>$model,
                    'controller'=>$controller,
                    'action'=>$action,
                    'updater'=>$this->user['id'],
                ), $id);
                if($is_update>0){
                    $this->write_log($this -> request -> getPost(),true,'菜单管理-更新成功');
                    $this->ajax_return('保存菜单成功',self::CODE_SUCCESS);
                }else{
                    $this->write_log($this -> request -> getPost(),false,'菜单管理-更新失败');
                    $this->ajax_return('保存菜单失败',self::CODE_SUCCESS);
                }
            }

        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this->ajax_return($e -> getMessage(),self::CODE_SUCCESS);
            die();
        }
    }

    /**
     * 更新操作
     */
    public function editAction(){
        try{
            if(!$this -> request -> isAjax()){
                throw new \Exception('非法请求');
            }
            $id = intval($this -> request -> getPost('id', 'trim'));
            $name = $this -> request -> getPost('name', 'remove_xss');
            $url = $this -> request -> getPost('url', 'remove_xss');
            $parentid = intval($this -> request -> getPost('parentid', 'trim'));

            $sort = intval($this -> request -> getPost('sort', 'trim'));
            $model = $this -> request -> getPost('model', 'remove_xss');
            $controller = $this -> request -> getPost('controller', 'remove_xss');
            $action = $this -> request -> getPost('action', 'remove_xss');
            /** 添加验证规则 */
            $this -> validator -> add_rule('name', 'required', '请填写菜单名称');
            $this -> validator -> add_rule('url', 'required', '请填写菜单链接');
            $this -> validator -> add_rule('url', 'menu_url', '菜单链接不规范');
            $this -> validator -> add_rule('model', 'required', '请填写模块名');
            $this -> validator -> add_rule('model', 'menu_mvc', '模块名称不规范');
            $this -> validator -> add_rule('controller', 'required', '请填写控制器名');
            $this -> validator -> add_rule('controller', 'menu_mvc', '控制器名称不规范');
            $this -> validator -> add_rule('action', 'required', '请填写方法名');
            $this -> validator -> add_rule('action', 'menu_mvc', '方法名称不规范');
            $this -> validator -> add_rule('sort', 'int', '排序只能为数字');
            /** 截获验证异常 */
            if ($error = $this -> validator -> run(array(
                'name' => $name,
                'url' => $url,
                'model' => $model,
                'controller' => $controller,
                'action' => $action,
                'sort' => $sort

            ))) {
                $error = array_values($error);
                $error = $error[0];
                $this->ajax_return($error['message'],self::CODE_ERROR);
            }

            //菜单数据入库
            if($id <= 0){
                //添加菜单
                $is_add = $this->menu_create(array(
                    'name' => $name,
                    'url' => $url,
                    'parent_id' => $parentid,
                    'sort' => $sort,
                    'model'=>$model,
                    'controller'=>$controller,
                    'action'=>$action,
                    'creater'=>$this->user['id']

                ));
                if($is_add>=0){
                    $this->write_log($this -> request -> getPost(),true,'菜单管理-添加成功');
                    $this->ajax_return('添加菜单成功',self::CODE_SUCCESS);
                }else{
                    $this->write_log($this -> request -> getPost(),false,'菜单管理-添加失败');
                    $this->ajax_return('添加菜单失败',self::CODE_SUCCESS);
                }
                die();
            }else{
                //更新菜单
                $is_update = $this->menu_update(array(
                    'name' => $name,
                    'url' => $url,
                    'parent_id' => $parentid,
                    'sort' => $sort,
                    'model'=>$model,
                    'controller'=>$controller,
                    'action'=>$action,
                    'updater'=>$this->user['id'],
                ), $id);
                if($is_update>0){
                    $this->write_log($this -> request -> getPost(),true,'菜单管理-更新成功');
                    //删除菜单缓存
                    $this -> get_repository('Menu') -> delete_menu_list_cache();
                    //获取超级管理员的角色信息
                    $admin = admin::findFirst(1);
                    $admin_role = $admin->toArray()['role_id'];
                    $this->get_repository('Admin')->del_menu_auth($admin_role);
                    //删除相关缓存信息
                    $role_menu = RoleMenu::find(
                        array(
                            'conditions' => 'menu_id = :menu_id:',
                            'bind' => array(
                                'menu_id' => $id,
                            ),
                        ));
                    if(!empty($role_menu->toArray())){
                        //删除缓存信息
                        foreach($role_menu->toArray() as $k=>$v){
                            $this->get_repository('Admin')->del_role_auth($v['role_id']);
                            $this->get_repository('Admin')->del_menu_auth($v['role_id']);
                        }

                    }
                    $this->get_repository('Admin')->del_menu_auth(1);
                    $this->ajax_return('保存菜单成功',self::CODE_SUCCESS);
                }else{
                    $this->write_log($this -> request -> getPost(),false,'菜单管理-更新失败');
                    $this->ajax_return('保存菜单失败',self::CODE_SUCCESS);
                }
            }

        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this->ajax_return($e -> getMessage(),self::CODE_SUCCESS);
            die();
        }
    }

    /**
     * 添加数据
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function menu_create($data){
        // 获取菜单路径
        $path = '/0/';
        if(isset($data['parent_id']) && !empty($data['parent_id'])){
            //获取父菜单的路径
            $parent_id = $data['parent_id'];
                $result = Menu:: findFirst(
                    array(
                'conditions' => 'id = :id: AND status = :status:',
                'bind' => array(
                    'id' => $parent_id,
                    'status' => 1
                ),
            ));
            $menu = array();
            if($result){
                $menu = $result -> toArray();
            }
            $path = $menu['path'];

            if(empty($path)){
                throw new \Exception('获取菜单路径失败');
            }

            $path .= "{$data['parent_id']}/";
        }

        $data['path'] = $path;
        $level = explode('/',$path);
        array_pop($level);
        array_shift($level);
        $data['level'] = count($level);
        //菜单数据入库
        $menu = new Menu();
        $id = $menu->create($data);
        $id = intval($id);
        if($id <= 0){
            throw new \Exception('获取新增菜单ID失败');
        }
        //删除菜单缓存
        $this -> get_repository('Menu') -> delete_menu_list_cache();

        return $id;
    }

    /**
     * 更新数据
     * @param $data
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function menu_update($data,$id){
        $id = intval($id);
        if(count($data) == 0 || $id <= 0){
            throw new \Exception('参数错误');
        }
        if(isset($data['parent_id']) && $data['parent_id'] == $id){
            throw new \Exception('不能选择本菜单作为父菜单');
        }
        /** 获取菜单路径 */
        $path = '/0/';
        if(isset($data['parent_id']) && !empty($data['parent_id'])){
            $parent_id = $data['parent_id'];
            $result = Menu:: findFirst(
                array(
                    'conditions' => 'id = :id: AND status = :status:',
                    'bind' => array(
                        'id' => $parent_id,
                        'status' => 1
                    ),
                ));
            $menu = array();
            if(!empty($result)){
                $menu = $result -> toArray();
            }
            else{
                throw new \Exception('获取菜单路径失败');
            }
            $path = $menu['path'];

            if(empty($path)){
                throw new \Exception('获取菜单路径失败');
            }

            $path .= "{$data['parent_id']}/";
        }
        $data['path'] = $path;
        $level = explode('/',$path);
        array_pop($level);
        array_shift($level);
        $data['level'] = count($level);
        //更新菜单数据
        if(isset($data['sort']) && ($data['sort'] <= 0 || $data['sort'] > 999)){
            $data['sort'] = 999;
        }

        $user=Menu::findFirst($id);
        $user->name=$data['name'];
        $user->url=$data['url'];
        $user->parent_id=$data['parent_id'];
        $user->sort=$data['sort'];
        $user->model=$data['model'];
        $user->controller=$data['controller'];
        $user->action=$data['action'];
        $user->updater= $data['updater'];
        $user->level=$data['level'];

        $is_update = $user->update();
        //删除菜单缓存
        $this -> get_repository('Menu') -> delete_menu_list_cache();

        return $is_update;
    }

}