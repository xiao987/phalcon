<?php

/**
 * 菜单模型
 * @category zgcdb0001
 * @copyright Copyright (c) 2016 zgcdb0001 team (http://www.kuaishangxian.com.cn)
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Models;

use \Marser\App\Models\BaseModel;

class MenuModel extends BaseModel{

    const TABLE_NAME = 'menu';

    public function initialize(){
        parent::initialize();
        $this -> set_table_source(self::TABLE_NAME);
    }

    /**
     * 	Is executed before the fields are validated for not nulls/empty strings
     *  or foreign keys when an insertion operation is being made
     */
    public function beforeValidationOnCreate(){
        if($this -> sort <= 0 || $this -> sort > 999){
            $this -> sort = 999;
        }
    }

    /**
     * 添加菜单数据
     * @param array $data
     * @return \Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     * @throws \Exception
     */
    public function insert_record(array $data){
        if(!is_array($data) || count($data)==0){
            throw new \Exception('参数错误');
        }
        $result = $this -> create($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $id = $this -> id;
        return $id;
    }

    /**
     * 自定义的update事件
     * @param array $data
     * @return array
     */
    protected function before_update(array $data){
        if(isset($data['sort']) && ($data['sort'] <= 0 || $data['sort'] > 999)){
            $data['sort'] = 999;
        }
        $data['update_time'] = time();

        return $data;
    }

    /**
     * 更新菜单数据
     * @param array $data
     * @param $id
     * @return int
     * @throws \Exception
     */
    public function update_record(array $data, $id){
        $id = intval($id);
        if(count($data) == 0 || $id <= 0){
            throw new \Exception('参数错误');
        }
        $data = $this -> before_update($data);

        $this -> id = $id;
        $result = $this -> iupdate($data);
        if(!$result){
            throw new \Exception(implode(',', $this -> getMessages()));
        }
        $affectedRows = $this -> db -> affectedRows();
        $affectedRows = intval($affectedRows);
        return $affectedRows;
    }

    /**
     * 获取菜单数据
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function detail($id){
        $id = intval($id);
        if($id <= 0){
            throw new \Exception('参数错误');
        }
        $result = $this -> findFirst(array(
            'conditions' => 'id = :id: AND status = :status:',
            'bind' => array(
                'id' => $id,
                'status' => 1
            ),
        ));
        $menu = array();
        if($result){
            $menu = $result -> toArray();
        }
        return $menu;
    }

    /**
     * 获取菜单树
     * @param int $status
     * @return array
     */
    public function get_menu_for_tree($status=1){
        $menuList = array();
        $status = intval($status);
        $result = $this -> find(array(
            'columns' => 'id, name, url, parent_id, path, sort, update_time,model,action,controller,level',

            'conditions' => 'status = :status:',
            'bind' => array(
                'status' => $status,
            ),
            'order' => 'parent_id DESC, sort ASC,level ASC',
        ));
        if($result){
            $menuList = $result -> toArray();
        }
        return $menuList;
    }

    /**
     * 更新菜单路径（使用的原生PDO处理，所以占位符与phalcon封装的占位符不一致，请注意）
     * @param $newPath
     * @param $oldPath
     * @return int
     */
    public function update_path($newPath, $oldPath){
        if(empty($newPath) || empty($oldPath)){
            throw new \Exception('参数错误');
        }
        $sql = "UPDATE " . $this -> getSource() . " SET path=REPLACE(path, :oldPath, :newPath) ";
        $sql .= ' WHERE `path` like :path AND `status` = :status ';
        $stmt = $this -> db -> prepare($sql);
        $bind = array(
            'oldPath' => "{$oldPath}",
            'newPath' => "{$newPath}",
            'path' => "{$oldPath}%",
            'status' => 1
        );
        $result = $stmt -> execute($bind);
        if(!$result){
            throw new \Exception('更新菜单路径失败');
        }
        $affectedRows = $stmt -> rowCount();
        return $affectedRows;
    }

    /**
     * 更新parent_id

     * @param $newParentid
     * @param $oldParentid
     * @return int
     * @throws \Exception
     */
    public function update_parentid($newParentid, $oldParentid){
        $newParentid = intval($newParentid);
        $oldParentid = intval($oldParentid);
        if($oldParentid <= 0){

        }
        $result = $this -> db -> update(
            $this -> getSource(),
            array('parent_id'),

            array($newParentid),
            array(
                'conditions' => 'parent_id = ? AND `status` = ? ',
                'bind' => array($oldParentid, 1)
            )
        );
        if(!$result){
            throw new \Exception('更新父菜单ID失败');
        }
        $affectedRows = $this -> db -> affectedRows();
        return $affectedRows;
    }
}