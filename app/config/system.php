<?php

/**
 * 系统配置--开发环境
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

return new \Phalcon\Config(array(
    'app' => array(
        //项目名称
        'app_name' => '赚个车',

        //版本号
        'version' => '1.0',

        //根命名空间
        'root_namespace' => 'Marser',

        //前台配置
        'frontend' => array(
            //模块在URL中的pathinfo路径名
            'module_pathinfo' => '/',

            //控制器路径
            'controllers' => BASE_PATH . '/app/frontend/controllers/',

            //视图路径
            'views' => BASE_PATH . '/app/frontend/views/',

            //是否实时编译模板
            'is_compiled' => true,

            //模板路径
            'compiled_path' => BASE_PATH . '/app/cache/compiled/frontend/',

            //前台静态资源URL
            'assets_url' => '/home/',
        ),

        //后台配置
        'backend' => array(
            //模块在URL中的pathinfo路径名
            'module_pathinfo' => '/admin/',

            //控制器路径
            'controllers' => BASE_PATH . '/app/backend/controllers/',

            //视图路径
            'views' => BASE_PATH . '/app/backend/views/',

            //是否实时编译模板
            'is_compiled' => true,

            //模板路径
            'compiled_path' => BASE_PATH . '/app/cache/compiled/backend/',

            //后台静态资源URL
            'assets_url' => '/admin/',
        ),

        //类库路径
        'libs' => BASE_PATH . '/app/libs/',

        //日志根目录
        'log_path' => BASE_PATH . '/app/cache/logs/',

        //缓存路径
        'cache_path' => BASE_PATH . '/app/cache/data/',
    ),
    
));