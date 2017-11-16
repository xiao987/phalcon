<?php

/**
 * 菜单管理
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace  Marser\App\Backend\Controllers;
use Marser\App\Models\Menu;
use Marser\App\Models\Admin;
use Marser\App\Models\RoleMenu;

use \Marser\App\Backend\Controllers\BaseController;

class MenuController extends BaseController{

    public function initialize(){
        parent::initialize();
    }
    /**
     * 菜单列表
     */
    public function indexAction(){
        try{
            /** 获取菜单列表 */
            $menuList = $this -> get_repository('Menu') -> get_menu_list();
            $this -> view -> setVars(array(
                'menuList' => $menuList,
            ));
            $this -> view -> pick('menu/index');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            return $this -> redirect();
        }
    }

    /**
     * 添加菜单
     */
    public function addAction(){
        try{
            $id = intval($this -> request -> get('id', 'trim'));
            $parentid = intval($this -> request -> get('parentid', 'trim'));

            $menu = array();
            if($id > 0){
                /** 获取菜单数据 */
                $menu = $this -> get_repository('Menu') -> detail($id);
            }
            /** 获取菜单树 */
            $menuList = $this -> get_repository('Menu') -> get_menu_list();

            $this -> view -> setVars(array(
                'menu' => $menu,
                'parentid' => $parentid,

                'menuList' => $menuList,
            ));
            $this -> view -> pick('menu/write');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            return $this -> redirect();
        }
    }

    /**
     *  编辑菜单
     */
    public function editAction(){
        try{
            $id = intval($this -> request -> get('id', 'trim'));
            $parentid = intval($this -> request -> get('parentid', 'trim'));

            $menu = array();
            if($id > 0){
                /** 获取菜单数据 */
                $menu = $this -> get_repository('Menu') -> detail($id);
            }
            /** 获取菜单树 */
            $menuList = $this -> get_repository('Menu') -> get_menu_list();

            $this -> view -> setVars(array(
                'menu' => $menu,
                'parentid' => $parentid,

                'menuList' => $menuList,
            ));
            $this -> view -> pick('menu/edit');
        }catch(\Exception $e){
            $this -> write_exception_log($e);

            $this -> flashSession -> error($e -> getMessage());

            return $this -> redirect();
        }
    }

   
    /**
     * 清除菜单缓存
     */
    public function refreshAction(){
        if($this -> get_repository('Menu') -> delete_menu_list_cache()){
            $this -> flashSession -> success('清除菜单缓存成功');
        }else{
            $this -> flashSession -> error('清除菜单缓存失败');
        }
        return $this -> redirect();
    }

    /**
     * 更新菜单排序
     */
    public function savesortAction(){
        try{
            $id = intval($this -> request -> get('id', 'trim'));
            $sort = intval($this -> request -> get('sort', 'trim'));

            $affectedRows = $this -> get_repository('Menu') -> update_record(array(
                'sort' => $sort,
                'updater'=>$this->user['id'],
            ), $id);
            if(!$affectedRows){
                throw new \Exception('更新菜单排序失败');
            }
            $this->write_log([$id],true,'菜单管理-排序更新成功');
            $this -> ajax_return('更新菜单排序成功');
        }catch(\Exception $e){
            $this -> write_exception_log($e);
            $this->write_log([$id],false,'菜单管理-排序更新失败');
            $this -> ajax_return($e -> getMessage());
        }
        $this -> view -> disable();
    }

    /**
     * 删除菜单
     */
    public  function delAction(){
        try{
            $id = intval($this -> request -> get('id', 'trim'));
            $menu = Menu::findFirst($id);
            if(!empty($menu->id))
            {
                //有子菜单的权限菜单不能被删除
                $is_son = Menu::find(
                    array(
                        'conditions' => 'parent_id = :parent_id:',
                        'bind' => array(
                            'parent_id' => $id,
                        ),
                    )
                );

                if(!empty($is_son->toArray())){
                    throw new \Exception('该菜单有子菜单，不可删除！');
                }else{
                    if($menu->delete()){
                        //删除role_menu表关联数据
                        $role_menu = RoleMenu::find(
                            array(
                                'conditions' => 'menu_id = :menu_id:',
                                'bind' => array(
                                    'menu_id' => $id,
                                ),
                            ));
                        if(!empty($role_menu->toArray())){
                            $role_menu->delete();
                            //删除缓存信息
                            foreach($role_menu->toArray() as $k=>$v){
                                $this->get_repository('Admin')->del_role_auth($v['role_id']);
                                $this->get_repository('Admin')->del_menu_auth($v['role_id']);
                            }

                        }
                        //删除菜单缓存
                        $this -> get_repository('Menu') -> delete_menu_list_cache();
                        //获取超级管理员的角色信息
                        $admin = admin::findFirst(1);
                        $admin_role = $admin->toArray()['role_id'];
                        $this->get_repository('Admin')->del_menu_auth($admin_role);
                        $this->write_log([$id],true,'菜单管理-删除成功');
                        $this -> flashSession -> success('删除菜单成功');
                    }else{
                        throw new \Exception('菜单信息删除失败！');
                    }
                }

            }else{
                throw new \Exception('未查到该菜单信息！');
            }
        }catch (\Exception $e){
            $this -> write_exception_log($e);
            $this->write_log([$id],false,'菜单管理-删除失败');
            $this -> flashSession -> error($e -> getMessage());
        }
        return $this -> redirect();
    }

}