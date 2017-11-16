<?php

/**
 * 用户业务仓库
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Backend\Repositories;
use Marser\App\Models\Menu;
use Marser\App\Models\Role;
use Marser\App\Models\RoleMenu;
use \Marser\App\Backend\Repositories\BaseRepository;

class Admin extends BaseRepository{
    /**
     * 分类缓存key
     */
    const ROLE_AUTH_LIST = 'role_auth_list_';
    /**
     * 分类缓存key
     */
    const MENU_AUTH_LIST = 'menu_auth_list_';
    /**
     * 分类缓存周期（秒） 一个月
     */
    const CACHE_TTL = 2592000;
    public function __construct(){

        parent::__construct();
    }

    /**
     * 登录态检测
     * @return bool
     */
    public function login_check(){
        if($this -> getDI() -> get('session') -> has('adminer')){
            if(!empty($this -> getDI() -> get('session') -> get('adminer')['id'])){
                return true;
            }
        }
        return false;
    }

    /**
     * 登录处理
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function login($username, $password){
        /** 获取用户信息 */
        $user = $this -> detail($username);
        if(!$user){
            throw new \Exception('用户名或密码错误');
        }
        $userinfo = $user -> toArray();
        /** 校验密码 */
        if(!$this ->check_password($password,$userinfo['password'],$userinfo['salt'])){
            throw new \Exception('用户名或密码错误，请重新输入');
        }
        /** 设置session */
        unset($userinfo['password']);
        $this -> getDI() -> get('session') -> set('adminer', $userinfo);
    }
    /**
     * 密码校验
     * @param string password 用户输入密码
     * @param string passworddb 数据加密密码
     * @param string saltdb  数据库salt
     */
    function check_password($password,$passworddb,$salt){
        $password=$this->sp_password($password,$salt);
        if ($password===$passworddb){
            return true;
        }else{
            return false;
        }
    }

    /**
     * CMF密码加密方法
     * @param string $pw 要加密的字符串
     * @return string
     */
    function sp_password($password,$salt){
        $result=md5(md5($password).$salt);
        return $result;
    }
    /**
     * 随机字符串生成
     * @param int $len 生成的字符串长度
     * @return string
     */
    function sp_random_string($len = 6) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 重置密码
     * @param $oldpwd
     * @param $newpwd
     * @return mixed
     * @throws \Exception
     */
    public function update_password($oldpwd, $newpwd){
        /** 校验旧密码是否正确 */
        $user = $this -> detail($this -> getDI() -> get('session') -> get('adminer')['username']);
        if(!$user){
            throw new \Exception('密码错误');
        }
        $userinfo = $user -> toArray();
        if(!$this ->check_password($oldpwd,$userinfo['password'],$userinfo['salt'])){
            throw new \Exception('密码错误，请重新输入');
        }
        /** 密码更新 */
        $salt=$this->sp_random_string();
        $password=$this->sp_password($newpwd,$salt);
        $affectedRows = $this -> get_model('AdminModel') -> update_record(array(
            'password' => $password,
            'salt' =>$salt,
        ), $this -> getDI() -> get('session') -> get('adminer')['id']);
        if(!$affectedRows){
            throw new \Exception('修改密码失败，请重试');
        }
        return $affectedRows;
    }

    /**
     * 更新个人信息
     * @param array $data
     * @param null $id
     * @return bool
     * @throws \Exception
     */
    public function update(array $data, $id){
        $id = intval($id);
        if($id <= 0){
            throw new \Exception('参数错误');
        }
        $affectedRows = $this -> get_model('AdminModel') -> update_record($data, $id);
        if(!$affectedRows){
            throw new \Exception('修改个人设置失败');
        }
        return true;
    }

    /**
     * 获取用户数据
     * @param string $username
     * @param array $ext
     * @return \Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function detail($username, array $ext=array()){
        $adminer = $this->get_model('AdminModel')->detail($username, $ext);
        if (!$adminer->id) {

            throw new \Exception('获取用户信息失败');
        }
        return $adminer;
    }
    /**
     * 获取用户权限
     * @param string $username
     * @param array $ext
     * @return \Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function get_role_list_by_role_id($role_id,$is_super_admin=false){
        $allow_auth_array = [];
        if(empty($role_id))
            return $allow_auth_array;


        $cache = $this -> getDI() -> get('cache');
        $cache_key = self::ROLE_AUTH_LIST.$role_id;
        if($cache -> exists($cache_key, self::CACHE_TTL)){
            $allow_auth_array = $cache -> get($cache_key, self::CACHE_TTL);
            return json_decode($allow_auth_array, true);
        }
        /** 设置缓存 */
//        $role = Role::findFirst($role_id);
//        $role_list = $role->toArray()['purviewids'];
        $rights_id_all = RoleMenu::find(
            array(
                'conditions' => ' role_id = :role_id:',
                'bind' => array(
                    'role_id' => $role_id,
                ),)
        );
        $rights_id_all=$rights_id_all->toArray();

        foreach($rights_id_all as $key=>$val){
            $rights_id[$key] =$val['menu_id'];
        }
        $role_list = implode(',',$rights_id);
        $menu_list = Menu::find("id IN (".$role_list.")");

        foreach ($menu_list->toArray() as $key => $menu) {
            
            if(isset($menu['controller']) && $menu['level']!=1)
            {
                $menu['controller'] = strtolower($menu['controller']);
                $menu['action'] = strtolower($menu['action']);
                $allow_auth_array[$menu['controller']."/".$menu['action']] = 1;
            }
        }
        $cache -> save($cache_key, json_encode($allow_auth_array), self::CACHE_TTL);
        return $allow_auth_array;
    }

    /**
     * 获取用户菜单
     * @param string $username
     * @param array $ext
     * @return \Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function get_menu_list_by_role_id($role_id,$is_super_admin=false){
        $menu_auth_array = [];
        if(empty($role_id))
            return $menu_auth_array;

    
        $cache = $this -> getDI() -> get('cache');
        $cache_key = self::MENU_AUTH_LIST.$role_id;
        if($cache -> exists($cache_key, self::CACHE_TTL)){
            $menu_auth_array = $cache -> get($cache_key, self::CACHE_TTL);
            return json_decode($menu_auth_array, true);
        }
        /** 设置缓存 */
        if($is_super_admin==true){
            $menu_list = Menu::find("status = 1");
        }
        else{
//            $role = Role::findFirst($role_id);
//            $role_list = $role->toArray()['purviewids'];
            $rights_id_all = RoleMenu::find(
                array(
                    'conditions' => ' role_id = :role_id:',
                    'bind' => array(
                        'role_id' => $role_id,
                    ),)
            );
            $rights_id_all=$rights_id_all->toArray();

            foreach($rights_id_all as $key=>$val){
                $rights_id[$key] =$val['menu_id'];
            }
            $role_list = implode(',',$rights_id);
            $menu_list = Menu::find("id IN (".$role_list.")");
        }
        foreach ($menu_list->toArray() as $key => $menu) {
            if($menu['level']!=3){
                 if ($menu['parent_id']==0){
                    $menu_auth_array[]=array(
                        'id'=>$menu['id'],
                        'parent_id'=>$menu['parent_id'],
                        'name'=>$menu['name'],
                    );
                }
                else{ 
                    $menu_auth_array['second'][]=array(
                        'id'=>$menu['id'],
                        'parent_id'=>$menu['parent_id'],
                        'name'=>$menu['name'],
                        'url'=>$menu['url'],
                    );
                }
            }
        }

        $cache -> save($cache_key, json_encode($menu_auth_array), self::CACHE_TTL);
        
        return $menu_auth_array;
    }

    public function del_role_auth($role_id){

        $cache = $this -> getDI() -> get('cache');
        $cache_key = self::ROLE_AUTH_LIST.$role_id;
        return $cache -> delete($cache_key);

    }
    public function del_menu_auth($role_id){

        $cache = $this -> getDI() -> get('cache');
        $cache_key = self::MENU_AUTH_LIST.$role_id;
        return $cache -> delete($cache_key);

    }
}