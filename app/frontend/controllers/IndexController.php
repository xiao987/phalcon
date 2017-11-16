<?php

/**
 * 首页
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Frontend\Controllers;

use \Marser\App\Frontend\Controllers\BaseController;

class IndexController extends BaseController{

    /**
     * 首页跳转
     */
    public function indexAction(){

        echo 'This is frontend';die;
//        $this -> dispatcher -> forward(array(
//            'controller' => 'article',
//            'action' => 'list',
//        ));
    }

    /**
     * 404 not found
     */
    public function notfoundAction(){
        $this -> view -> disableLevel(array(
            /** 关闭分层渲染 */
            \Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT => false,
        ));
        $this -> view -> pick('index/404');
    }

}