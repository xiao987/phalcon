<?php

/**
 * 注册命文件
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

$loader = new \Phalcon\Loader();

/**
 * 注册命名空间
 */
$loader -> registerNamespaces(array(
    'Marser' => BASE_PATH,

    'Marser\App\Core' => BASE_PATH . '/app/core',
    'Marser\App\Helpers' => BASE_PATH . '/app/helpers',
    'Marser\App\Library' => BASE_PATH . '/app/library',
    'Marser\App\Service' => BASE_PATH . '/app/service',
    'Marser\App\Tasks' => BASE_PATH . '/app/tasks',

    'Marser\App\Frontend\Controllers' => BASE_PATH . '/app/frontend/controllers',
    'Marser\App\Frontend\Controllers\Sp_api' => BASE_PATH . '/app/frontend/controllers/sp_api',
    'Marser\App\Frontend\Repositories' => BASE_PATH . '/app/frontend/repositories',

    'Marser\App\Backend\Controllers' => BASE_PATH . '/app/backend/controllers',
    'Marser\App\Backend\Controllers\Ajax' => BASE_PATH . '/app/backend/controllers/ajax',
    'Marser\App\Models' => BASE_PATH . '/app/models',
    'Marser\App\Backend\Repositories' => BASE_PATH . '/app/backend/repositories',
)) -> register();