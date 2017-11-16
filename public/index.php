<?php

use Phalcon\Di\FactoryDefault;
/**
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */
/**
 * 设置环境变量配置信息
 */
define('ENVIRONMENT', isset($_SERVER['Phalcon_ENV']) ? $_SERVER['Phalcon_ENV'] : 'development');
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'testing':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
ini_set('display_errors','On');
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
try {

      /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Read services
     */
    include_once APP_PATH . '/core/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();


    /**
     * Include Autoloader
     */
    include_once APP_PATH . '/core/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    /**
     * 加入模块配置
     */
    include_once APP_PATH . '/config/module.php';

    /**
     * Handle routes
     */
    include_once APP_PATH . '/config/routers.php';

    /**
     * 引入composer自动加载类
     */
    include_once BASE_PATH . "/vendor/autoload.php";


    echo str_replace(["\n", "\r", "\t"], '', $application->handle()->getContent());

}catch (\Exception $e) {
    $log = array(
        'file' => $e -> getFile(),
        'line' => $e -> getLine(),
        'code' => $e -> getCode(),
        'msg' => $e -> getMessage(),
        'trace' => $e -> getTraceAsString(),
    );
    var_dump($e -> getMessage());
    $date = date('Ymd');
    $logger = new \Phalcon\Logger\Adapter\File(BASE_PATH."/app/cache/logs/crash_{$date}.log");
    $logger -> error(json_encode($log));
}
