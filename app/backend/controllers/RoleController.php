<?php

namespace Marser\App\Backend\Controllers;

use Marser\App\Library\Validator;
use Marser\App\Models\Menu;
use Marser\App\Models\Role;
use Marser\App\Models\Admin;
use Marser\App\Models\RoleMenu;
use function PHPSTORM_META\elementType;
use Phalcon\Paginator\Adapter\Model as Paginator;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16 0016
 * Time: 14:47
 */
class RoleController extends BaseController
{

    public function initialize()
    {
        parent::initialize();
    }
    //角色列表
    public function indexAction()
    {
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");


        $roles = Role::find([
            'order'   => 'id',
            'columns' => 'id,title,purviewids,description,create_ip,create_time',
        ]);
        $authoritys = array("role/add","menu/refresh","role/update","role/del","role/changeAuth");
        $auth_result = $this->check_authority($authoritys);
        $this->view->setVars(array(
            'auth_list'=>$auth_result,
        ));
        $paginator = new Paginator([
            'data' => $roles,
            'limit'=> 15,
            'page' => $numberPage
        ]);
        $this->view->page = $paginator->getPaginate();
    }
    //角色添加
    public function addAction()
    {
        $menuList = $this->get_repository('Menu')->get_menu_list();
        $this->view->setVars(array(
            'menuList' => $menuList,
        ));
    }
    //删除角色
    public function delAction()
    {
        $role_id = intval($this->request->get('id'));
        $role = Role::findFirst($role_id);
        try{
            if(empty($role))
            {
                throw new Exception("该数据不存在");
                
            }

            //admin表中存在已被使用不能删除
            $user_num = Admin::count(
                array(
                    'conditions' => 'role_id = :role_id:',
                    'bind' => array(
                        'role_id' => $role_id,
                    ),)
            );
            if($user_num>0){
                throw new Exception("角色有被用户使用，不可删除!");
            }

            //删除role_menu记录
            $role_menu_list = RoleMenu::find(
                array(
                    'conditions' => 'role_id = :role_id:',
                    'bind' => array(
                        'role_id' => $role_id,
                    ),)
            );

            if(!empty($role_menu_list->toArray())){
                //进行删除role_menu信息
                if($role_menu_list->delete()){
                    //删除缓存信息
                    $this->get_repository('Admin')->del_role_auth($role_id);
                    $this->get_repository('Admin')->del_menu_auth($role_id);
                }
               
            }
            if(!$role->delete())
            {
                throw new Exception("删除失败!");
            }
            
            $this->write_log([$role_id],true,"删除角色-删除成功");
            $this->flashSession->success('删除成功!');
        }
        catch(Exception $e){
             $this->flashSession->error('删除失败!'.$e->getMessage());
             $this->write_log([$role_id],false,"删除角色-删除失败".$e->getMessage());
        }
        
        return $this->redirect();
    }


    //权限添加
    public function changeAuthAction()
    {
        
        try {
            if($this -> request -> isAjax()){
                throw new \Exception('非法请求');
            }

            $role_id=$this->request->get('id');
            $role = Role::findFirst($role_id);
            if(empty($role)){

                throw new \Exception('该数据不存在');
            } 
            //获取menu_id
            $rights_id_all = RoleMenu::find(
                array(
                    'conditions' => ' role_id = :role_id:',
                    'bind' => array(
                        'role_id' => $role_id,
                    ),)
            );
            
            $rights_id = [];
            foreach($rights_id_all->toArray() as $key=>$val){
                $rights_id[$key] = $val['menu_id'];
            }
            /** 获取菜单列表 */
            $menuList = $this->get_repository('Menu')->get_menu_list();
            $new_menu_array = array();
            foreach ($menuList as $key => $value) {

                $value['checked'] = in_array($value['id'],$rights_id)?"checked":"";
                switch ($value['level']) {
                    case '1':
                        $new_menu_array[$value['id']] = $value;
                        break;
                    case '2':
                        $new_menu_array[$value['parent_id']]['second'][$value['id']] = $value;
                        $new_menu_array[$value['parent_id']]['second_num']+=1;
                        # code...
                        break;
                    case '3':
                        $levels = explode('/', $value['path']);
                        $new_menu_array[$levels[2]]['second'][$value['parent_id']]['third'][$value['id']] = $value;
                        # code...
                        break;
                    default:
                        # code...
                        break;
                }
            }


      

            $this->view->setVars(array(
                'menuList' => $menuList,
                'role'=>$role_id,
                'rights'=>$rights_id,
                'menu_array' => $new_menu_array,
                "menu"=>array()
            ));
        } catch (\Exception $e) {
            $this->write_exception_log($e);

            $this->flashSession->error($e->getMessage());

            return $this->redirect('role/index');
        }
    }

}