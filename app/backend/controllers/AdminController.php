<?php
namespace Marser\App\Backend\Controllers;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16 0016
 * Time: 14:47
 */

use Marser\App\Models\Adminer_log;
use Marser\App\Models\Role;
use Marser\App\Models\Admin;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Http\Response;
class AdminController extends BaseController{

    public function initialize(){
        parent::initialize();
    }
    //获取用户列表
    public function indexAction()
    {
        $admin=Admin::find([
            'order'=>'id',
            'columns'=>'id,username,realname,phone_number,intro,status,creater,create_time,updater,update_time,grade'
        ]);
        $authoritys = array("admin/add","menu/refresh","admin/edit","admin/delete");
        $auth_result = $this->check_authority($authoritys);

        $this -> view -> setVars(array(
            'users' => $admin,
            'auth_list' => $auth_result
        ));
    }
    //用户添加
    public function addAction(){
        $roles=Role::find([
            'order'=>'id',
            'columns'=>'id,title,purviewids',
        ]);
        $this -> view -> setVars(array(
            'roles' => $roles,
        ));

    }

    //编辑用户
    public function editAction()
    {
        $id=(int)$this->request->get('id');
        $user=Admin::findFirst($id);
        if(empty($user)){
            $this->flashSession->error('该数据不存在!');
            return $this->redirect("admin/index");
        }
        $roles=Role::find([
            'order'=>'id',
            'columns'=>'id,title,purviewids',
        ]);
        $this->view->setVars(array(
            'user' => $user,
            'roles' => $roles,
            'id'=>$id
        ));
    }
    //删除用户
    public function delAction()
    {
        $user_id=(int)$this->request->get('id');
        $user=Admin::findFirst($user_id);
        if (!user==false){
            if ($user->delete()===false){
                $this->write_log([$user_id],false,'管理员管理-删除失败');
                $this->flashSession->error('删除失败!');
            }else{
                $this->write_log([$user_id],true,'管理员管理-删除成功');
                $this->flashSession->success('删除成功!');
            }
        }
        return $this->redirect();
    }
    //操作日志展示
    public function logAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Products', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }


        $logs=Adminer_log::find([
            'order'=>'id desc',
            'columns'=>'id,username,url,query,remark,create_time,create_ip',
        ]);
        if(empty($logs)){
            $this->flashSession->notice('日志为空,请联系超管！');
        }
        $paginator = new Paginator([
            'data' => $logs,
            'limit'=> 15,
            'page' => $numberPage
        ]);
//        echo '<pre>';
//        print_r($paginator->getPaginate());die;
        $this->view->page = $paginator->getPaginate();
    }

}
