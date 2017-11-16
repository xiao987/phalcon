<?php
namespace Marser\App\Models;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 12:32
 */
use Phalcon\Mvc\Model;
use \Marser\App\Models\BaseModel;
class Menu extends BaseModel
{
    public $id;

    public $name;
    public $url;

    public $sort;
    public $parent_id;
    public $path;
    public $status;
    public $creater;
    public $create_time;
    public $create_ip;
    public $updater;
    public $update_time;
    public $model;
    public $controller;
    public $action;
    public $level;

    public function initialize()
    {
        $this->setSource("menu");
        parent::initialize();
    }
    public function getSource()
    {
        return 'menu';
    }
}