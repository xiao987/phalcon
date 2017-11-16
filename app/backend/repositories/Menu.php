<?php

/**
 * 菜单仓库
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Backend\Repositories;

use \Marser\App\Backend\Repositories\BaseRepository,
    \Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class Menu extends BaseRepository{

    /**
     * 分类缓存key
     */
    const MENU_TREE_CACHE_KEY = 'menu_tree';

    /**
     * 分类缓存周期（秒） 一个月
     */
    const MENU_CACHE_TTL = 2592000;

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取菜单树
     * @return array
     */
    public function get_menu_list(){
        /** 从缓存中读取菜单数据 */
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::MENU_TREE_CACHE_KEY, self::MENU_CACHE_TTL)){
            $menuList = $cache -> get(self::MENU_TREE_CACHE_KEY, self::MENU_CACHE_TTL);
            $menuList = json_decode($menuList, true);
            if(is_array($menuList) && count($menuList) > 0){
                return $menuList;
            }
        }
        /** 从数据库读取全部菜单数据 */
        $menuList = $this -> get_menu_tree_list();
        /** 设置缓存 */
        $cache -> save(self::MENU_TREE_CACHE_KEY, json_encode($menuList), self::MENU_CACHE_TTL);


        return $menuList;
    }

    /**
     * 删除菜单树缓存
     * @return array
     */
    public function delete_menu_list_cache(){
        $cache = $this -> getDI() -> get('cache');
        if($cache -> exists(self::MENU_TREE_CACHE_KEY, self::MENU_CACHE_TTL)){
            return $cache -> delete(self::MENU_TREE_CACHE_KEY);
        }
        return true;
    }

    /**
     * 获取菜单数据
     * @param $id
     * @return mixed
     */
    public function detail($id){
        $menu = $this -> get_model('MenuModel') -> detail($id);
        return $menu;
    }

    /**
     * 更新菜单数据
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update_record(array $data, $id){
        $affectedRows = $this -> get_model('MenuModel') -> update_record($data, $id);
        if($affectedRows){
            /** 清除菜单缓存 */
            $this -> delete_menu_list_cache();
        }
        return $affectedRows;
    }

    /**
     * 删除菜单记录（软删除）
     * @param $id
     * @return bool
     */
    public function delete($id){
        $id = intval($id);
        if($id <= 0){
            throw new \Exception('请选择需要删除的菜单');
        }
        $menuModel = $this->get_model('MenuModel');
        $menuArray = $this -> get_menu_list();
        try {
            /** 创建单独的事务管理器，并请求一个事务 */
            $transactionManager = new TransactionManager();
            $transaction = $transactionManager -> get();

            $menuModel -> setTransaction($transaction);
            $affectedRows = $menuModel -> update_record(array(
                'status' => 0
            ), $id);
            if ($affectedRows <= 0) {
                $transaction -> rollback('删除菜单失败');
            }
            /** 若当前id不是叶子节点，更新其子菜单的parent_id和path */
            if (isset($menuArray[$id]) && $menuArray[$id]['leaf_node'] == 0) {
                $affectedRowsPath = $menuModel -> update_path($menuArray[$id]['path'], "{$menuArray[$id]['path']}{$id}/");
                if ($affectedRowsPath <= 0) {
                    $transaction -> rollback('更新菜单路径失败，影响行数为0');
                }

                $affectedRowsParentcid = $menuModel -> update_parentid($menuArray[$id]['parent_id'], $id);
                if ($affectedRowsParentcid <= 0) {

                    $transaction -> rollback('更新子菜单parent_id失败，影响行数为0');
                }
            }
            /** 事务提交 */
            $transaction -> commit();
            /** 清除分类缓存 */
            $this -> delete_menu_list_cache();
        }catch(\Phalcon\Mvc\Model\TransportException $e){
            throw new \Exception($e -> getMessage());
        }
    }

    /**
     * 保存菜单数据
     * @param array $data
     * @param $id
     */
    public function save(array $data, $id){
        $id = intval($id);
        if($id <= 0){
            /** 添加菜单 */
            $this -> create($data);
        }else{
            /** 更新菜单 */
            $this -> update($data, $id);
        }
    }

    /**
     * 新增菜单
     * @param array $data
     * @return int
     * @throws \Exception
     */
    protected function create(array $data){
        /** 获取菜单路径 */
        $path = '/0/';
        if(isset($data['parent_id']) && !empty($data['parent_id'])){
            /** 获取父菜单的路径 */

            $path = $this -> get_path_by_parentid($data['parent_id']);

            if(empty($path)){
                throw new \Exception('获取菜单路径失败');
            }
            $path .= "{$data['parent_id']}/";
        }
        $data['path'] = $path;
        /** 菜单数据入库 */
        $id = $this -> get_model('MenuModel') -> insert_record($data);
        $id = intval($id);
        if($id <= 0){
            throw new \Exception('获取新增菜单ID失败');
        }
        /** 清除菜单缓存 */
        $this -> delete_menu_list_cache();

        return $id;
    }

    /**
     * 更新菜单数据
     * @param array $data
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    protected function update(array $data, $id){

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
            /** 获取父菜单的路径 */

            $path = $this -> get_path_by_parentid($data['parent_id']);

            if(empty($path)){
                throw new \Exception('获取菜单路径失败');
            }
            $path .= "{$data['parent_id']}/";
        }
        $data['path'] = $path;
        /** 更新菜单数据 */
        $affectedRows = $this -> get_model('MenuModel') -> update_record($data, $id);
        if(!$affectedRows){

            throw new \Exception('更新菜单数据失败');
        }
        /** 清除菜单缓存 */
        $this -> delete_menu_list_cache();

        return $affectedRows;
    }

    /**
     * 获取父菜单路径
     * @param $parentid
     * @return string
     * @throws \Exception
     */
    protected function get_path_by_parentid($parentid){
        $path = '';
        $menu = $this -> get_model('MenuModel') -> detail($parentid);


        if(isset($menu['path']) && !empty($menu['path'])){
            $path = $menu['path'];
        }
        return $path;
    }

    /**
     * 获取菜单列表
     * @return array|mixed
     */
    protected function get_menu_tree_list(){
        $menuArray = array();
        /** 获取所有菜单数据 */
        $menuList = $this -> get_model('MenuModel') -> get_menu_for_tree();
        if(!is_array($menuList) || count($menuList) == 0){
            return $menuArray;
        }
        /** 数组索引替换 */
        foreach($menuList as $mk=>$mv){
            $menuArray[$mv['id']] = $mv;
        }
        unset($menuList);
        /** 迁移数组，形成数组树形结构 */
        foreach($menuArray as $k=>&$v){
            if(isset($menuArray[$v['parent_id']])){
                $menuArray[$v['parent_id']]['son'][$v['id']] = $v;
                unset($menuArray[$k]);
            }
        }
        /** 递归简化为二维数组 */
        $menuArray = $this -> recursive_menu_tree($menuArray);
        return $menuArray;
    }

    /**
     * 递归菜单树转成为二维数组
     * @param array $categoryTree
     * @return mixed
     */
    protected function recursive_menu_tree(array $menuArray){
        static $menuList = array();
        foreach($menuArray as $k=>$v){
            if(isset($v['son']) && is_array($v['son']) && count($v['son']) > 0){
                $temp = $v;
                unset($temp['son']);
                $menuList[$v['id']] = $temp;
                $menuList[$v['id']]['leaf_node'] = 0; //是否为叶子节点
                $this -> recursive_menu_tree($v['son']);
            }else{
                $menuList[$v['id']] = $v;
                $menuList[$v['id']]['leaf_node'] = 1; //是否为叶子节点
            }
        }
        return $menuList;
    }

}