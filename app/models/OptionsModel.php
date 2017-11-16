<?php

/**
 *
 * @category zgcdb0001
 * @copyright Copyright (c) 2016 zgcdb0001 team (http://www.kuaishangxian.com.cn)
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Models;

use \Marser\App\Models\BaseModel;

class OptionsModel extends BaseModel{

    const TABLE_NAME = 'options';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 获取配置项数据
     * @param array $ext
     * @return mixed
     * @throws \Exception
     */
    public function get_list(array $ext=array()){
        $result = $this -> find();
        if(!$result){
            throw new \Exception('获取配置数据失败');
        }
        $options = $result -> toArray();
        return $options;
    }

    /**
     * 更新配置项
     * @param array $data
     * @param $opkey
     * @return int
     * @throws \Exception
     */
    public function update_record(array $data, $opkey){
        if(count($data) == 0 || empty($opkey)){
            throw new \Exception('参数错误');
        }

        $keys = array_keys($data);
        $values = array_values($data);
        $result = $this -> db -> update(
            $this->getSource(),
            $keys,
            $values,
            array(
                'conditions' => 'op_key = ?',
                'bind' => array($opkey)
            )
        );
        if(!$result){
            throw new \Exception('更新失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }



}
