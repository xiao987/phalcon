<?php
namespace Marser\App\Models;

use Phalcon\Mvc\Model;
use \Marser\App\Models\BaseModel;
class Adminer_log extends BaseModel
{
    public $id;
    public $admin_id;
    public $username;
    public $url;
    public $query;
    public $remark;
    public $status;
    public $creater;
    public $create_time;
    public $create_ip;
    public $updater;
    public $update_time;
    /**
     * status 状态值是否成功 2否 1是
     * default 2
     */
    const OPERATION_YES = 1;
    const OPERATION_NO = 2;
    
    public function initialize()
    {
        $this->setSource("adminer_log");
        parent::initialize();
    }
    public function getSource()
    {
        return 'adminer_log';
    }
}