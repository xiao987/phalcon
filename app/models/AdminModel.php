<?php

/**
 * 用户model
 * @category zgcdb0001
 * @copyright Copyright (c) 2016 zgcdb0001 team (http://www.kuaishangxian.com.cn)
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Models;
use \Marser\App\Models\BaseModel;

class AdminModel extends BaseModel{

    const TABLE_NAME = 'admin';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 获取用户数据
     * @param $username
     * @param array $ext
     * @return \Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function detail($username, array $ext=array()){
        if(empty($username)){
            throw new \Exception('参数错误');
        }
        $params = array(
            'conditions' => 'username = :username:',
            'bind' => array(
                'username' => $username,
            ),
        );
        if(isset($ext['columns']) && !empty($ext['columns'])){
            $params['columns'] = $ext['columns'];
        }
        $result = $this -> findFirst($params);
        if(!$result){
            throw new \Exception('获取用户信息失败');
        }
        return $result;
    }

    /**
     * 自定义的update事件
     * @param array $data
     * @return array
     */
    protected function before_update(array $data){
        if(empty($data['update_time'])){
            $data['update_time'] = time();
        }
        return $data;
    }

    /**
     * 更新用户数据
     * @param array $data
     * @param int $id
     * @return int
     * @throws \Exception
     */
    public function update_record(array $data, $id){
        $uid = intval($id);
        if(count($data) == 0 || $id <= 0){
            throw new \Exception('参数错误');
        }
        $data = $this -> before_update($data);

        $this -> id = $id;
        $result = $this -> iupdate($data);
        if(!$result){
            throw new \Exception('更新失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }

}