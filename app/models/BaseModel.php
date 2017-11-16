<?php

/**
 * 基类model
 * @category zgcdb0001
 * @copyright Copyright (c) 2016 zgcdb0001 team (http://www.kuaishangxian.com.cn)
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Models;

use Phalcon\Validation;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use \Marser\App\Core\PhalBaseModel;

class BaseModel extends PhalBaseModel{
    /**
     * 数据库连接对象
     * @var \Phalcon\Db\Adapter\Pdo\Mysql
     */
    protected $db;
    public function initialize(){
        parent::initialize();
        $this -> db = $this -> getDI() -> get('db');
    }

    public function beforeValidationOnCreate(){
    	if(empty($this -> creater)){
            
            return false;
        }

    	$this -> updater = $this -> creater;
        $this -> update_time = $this -> create_time=time();
        if(empty($this -> create_ip)){
    		$this -> create_ip = $this->getDI()->getrequest()->getClientAddress();
            empty($this -> create_ip)?$this -> create_ip="127.0.0.1":'';
    	}
    }
    public function beforeValidationOnUpdate(){
    	if(empty($this -> updater)){
            return false;
    	}
    	$this -> update_time = time();

    }
}
