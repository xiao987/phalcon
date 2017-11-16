<?php

/**
 * 配置路由规则
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 *
 * 实例 ：支持正则
 * $key => array("controller" => "", "action" => "")
 */

return array(
    //后台路由规则
    '/admin.html' => array(
        'module' => 'backend',
        'controller'=>"dashboard",
        'action'=>"index"
    ),
    '/admin/:controller/:action/:params' => array(
        'module' => 'backend',
        'controller'=>1,
        'action'=>2
    ),
    '/admin/ajax/:controller/:action/:params' => array(
        'module' => 'backend',
        'namespace'=>'Marser\App\Backend\Controllers\Ajax',
        'controller'=>1,
        'action'=>2
    ),
    '/admin/:controller/:action.html' => array(
        'module' => 'backend',
        'controller'=>1,
        'action'=>2
    ),
    '/sp_api/:controller/:action/:params' => array(
        'module' => 'frontend',
        'namespace'=>'Marser\App\Frontend\Controllers\Sp_api',
        'controller'=>1,
        'action'=>2
    ),
    //前台路由规则home
    '/home/:controller/:action/:params' => array(
        'module' => 'frontend',
        'controller'=>1,
        'action'=>2
    ),
    '/home/:controller/:action.html' => array(
        'module' => 'frontend',
        'controller'=>1,
        'action'=>2
    ),
    //文章详情路由
    '/article/:int.html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'detail',
        'aid' => 1
    ),

    //搜索路由
    '/search.html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
    ),
    '/cat.html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
        'category' => 'qkl'
    ),
    //分类下的文章路由
    '/category/([a-zA-Z0-9\_\-]+).html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
        'category' => 1
    ),

    //标签下的文章路由
    '/tag/([a-zA-Z0-9\_\-]+).html' => array(
        'module' => 'frontend',
        'controller' => 'article',
        'action' => 'list',
        'tag' => 1
    ),

    //404页面路由
    '/404' => array(
        'module' => 'frontend',
        'controller' => 'index',
        'action' => 'notfound',
    )
);