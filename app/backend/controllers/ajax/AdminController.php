<?php
namespace Marser\App\Backend\Controllers\Ajax;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16 0016
 * Time: 14:47
 */
use Marser\App\Backend\Controllers\BaseController;
use Marser\App\Models\Admin;
use Marser\App\Models\Role;
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
        $this -> view -> setVars(array(
            'users' => $admin,
        ));
    }
    //用户添加
    public function addAction(){
        if(!$this -> request -> isAjax())
        {
            throw new \Exception('非法请求');
        }
        $username=$this->request->getPost("username");
        $password=$this->request->getPost('password');
        $realname=$this->request->getPost("realname");
        $phone_number=$this->request->getPost("phone_number");
        $email=$this->request->getPost("email","email");
        $intro=$this->request->getPost("intro");
        $role_id=$this->request->getPost('role_id');
        $role = Role::findFirst($role_id);
        if(empty($role)){
             $this->ajax_return("选择的角色不存在",self::CODE_ERROR);
        }
        /**进行验证*/
        $this -> validator -> add_rule('username', 'required', '请填写用户名');
        $this -> validator -> add_rule('username', 'username', '用户名格式不正确');
        $this -> validator -> add_rule('password', 'required', '请填写密码');
        $this -> validator -> add_rule('password', 'password', '密码格式不正确');
        $this -> validator -> add_rule('realname', 'required', '请填写真实姓名');
        $this -> validator -> add_rule('realname', 'realname', '请填写正确的真实姓名');
        $this -> validator -> add_rule('phone_number', 'required', '请填写电话号码');
        $this -> validator -> add_rule('phone_number', 'phone', '请填写正确的电话号码');
        $this -> validator -> add_rule('email', 'required', '请填写邮箱');
        $this -> validator -> add_rule('email', 'email', '请填写正确的邮箱地址');
        $this -> validator -> add_rule('intro', 'required', '请填写详细描述');
        $this -> validator -> add_rule('role_id', 'required', '请选择角色');
        /** 截获验证异常 */
        if ($error = $this -> validator -> run(array(
            'username' => $username,
            'password' => $password,
            'realname' => $realname,
            'phone_number' => $phone_number,
            'email' => $email,
            'intro' => $intro,
            'role_id' => $role_id,
        ))) {
            $error = array_values($error);
            $error = $error[0];
            $this->ajax_return($error['message'],self::CODE_ERROR);

        }

        $is_username = Admin::findFirst(
            array(
                'conditions' => 'username = :username:',
                'bind' => array(
                    'username' => $username,
                ),)
        );
        if($is_username){
            $this->ajax_return('用户名已被使用',self::CODE_ERROR);
        }else{
            $salt=$this->get_repository('Admin')->sp_random_string();
            $password=$this->get_repository('Admin')->sp_password($password,$salt);
            $user=new Admin();
            $user->username=$username;
            $user->password=$password;
            $user->realname=$realname;
            $user->phone_number=$phone_number;
            $user->email=$email;
            $user->intro=$intro;
            $user->salt=$salt;
            $user->creater= $this->user['id'];
            $user->role_id=$role_id;
            if ($user->save()){
                $this->write_log([$user->toArray()['id']],true,'管理员管理-添加成功');
                $this->ajax_return('管理员添加成功',self::CODE_SUCCESS);
            }else{
                $this->write_log([$user->toArray()['id']],false,'管理员管理-添加失败');
                $this->ajax_return('管理员添加失败',self::CODE_ERROR);
            }
        }

    }
    //用户保存
    public function editAction(){
        $id=$this->request->getPost('id');
        if(empty($id)){
            $this->ajax_return('缺少必要参数!',self::CODE_ERROR);
        }
        $user=Admin::findFirst($id);
        if(empty($user)){
            $this->ajax_return('该数据不存在!',self::CODE_ERROR);
        }
        $username=$this->request->getPost("username");
        $realname=$this->request->getPost("realname");
        $phone_number=$this->request->getPost("phone_number");
        $email=$this->request->getPost("email","email");
        $intro=$this->request->getPost("intro");
        $role_id=$this->request->getPost('role_id');
        
        $role = Role::findFirst($role_id);
        if(empty($role)){
             $this->ajax_return("选择的角色不存在",self::CODE_ERROR);
        }


        /**进行验证*/
        $this -> validator -> add_rule('realname', 'required', '请填写真实姓名');
        $this -> validator -> add_rule('realname', 'realname', '请填写正确的真实姓名');
        $this -> validator -> add_rule('phone_number', 'required', '请填写电话号码');
        $this -> validator -> add_rule('phone_number', 'phone', '请填写正确的电话号码');
        $this -> validator -> add_rule('email', 'required', '请填写邮箱');
        $this -> validator -> add_rule('email', 'email', '请填写正确的邮箱地址');
        $this -> validator -> add_rule('intro', 'required', '请填写详细描述');
        $this -> validator -> add_rule('role_id', 'required', '请选择角色');
        /** 截获验证异常 */
        if ($error = $this -> validator -> run(array(
            'realname' => $realname,
            'phone_number' => $phone_number,
            'email' => $email,
            'intro' => $intro,
            'role_id' => $role_id,
        ))) {
            $error = array_values($error);
            $error = $error[0];
            $this->ajax_return($error['message'],self::CODE_ERROR);
        }
        //修改数据
        $user->username=$user->toArray()['username'];
        $user->password=$user->toArray()['password'];
        $user->salt=$user->toArray()['salt'];
        $user->realname=$realname;
        $user->phone_number=$phone_number;
        $user->email=$email;
        $user->intro=$intro;
        $user->updater= $this->user['id'];
        $user->role_id=$role_id;
        if ($user->update()){
            $this->write_log([$user->toArray()['id']],true,'管理员管理-更新成功');
            $this->ajax_return('管理员更新成功',self::CODE_SUCCESS);
        }else{
            $this->write_log([$user->toArray()['id']],false,'管理员管理-更新失败');
            $this->ajax_return('管理员更新失败',self::CODE_ERROR);
        }
    }
    //删除用户
    public function deleteAction()
    {
        $user_id=(int)$this->request->get('id');
        $user=Admin::findFirst($user_id);
        if (!user==false){
            if ($user->delete()===false){
                $this->flashSession->error('删除失败!');
            }else{
                $this->flashSession->success('删除成功!');
            }
        }
        return $this->redirect();
    }

}
