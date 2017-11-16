<?php
namespace Marser\App\Models;

use Phalcon\Mvc\Model;
use \Marser\App\Models\BaseModel;
class Role extends BaseModel
{
    public $id;
    public $title;
    public $purviewids;
    public $description;
    public $isupdate;
    public $sort;
    public $status;
    public $create_time;
    public $create_by;
    public $create_ip;
    public $modify_by;
    public $modify_time;

    public function initialize()
    {
        $this->setSource("role");
        parent::initialize();
    }
    public function getSource()
    {
        return 'role';
    }

    /**
    *
    * 根据角色id获得所有权限
    */
    
    public static function get_role_list_by_role_id($role_id)
    {
        if(empty($role_id)){
            return false;
        }
        $role = Role::findFirst($role_id);
        $role_list_array = array();
        if(!empty($role)){
            $role_list=$role->toArray()['purviewids'];
            if(!empty($role_list)){
                $role_list_array=explode(',',$role_list);
            }
        }
        return $role_list_array;
    }
}