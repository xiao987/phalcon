<?php

/**
 *
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */
namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController;

class IndexController extends BaseController{

    /**
     * 控制面板
     */
    public function indexAction(){
        return $this -> redirect('dashboard/index');
    }

    /**
     * 404页面
     */
    public function notfoundAction(){
        return $this -> response -> setHeader('status', '404 Not Found');
    }
}