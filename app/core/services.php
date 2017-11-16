<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Cache\Frontend\Data as FrontendData;
use Phalcon\Cache\Backend\Redis;

/**
 * 注册事件分发器
 */
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Db\Profiler as DbProfiler;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;




/**
 * 设置路由
 */

$di -> set('router', function(){
    $router = new \Phalcon\Mvc\Router();
    $router -> setDefaultModule('frontend');
    //var_dump($router->getMode ());die;
    //$router -> setMode(Phalcon\Router::MODE_REST);
    $routerRules = new \Phalcon\Config\Adapter\Php(BASE_PATH . "/app/config/routers.php");
    foreach ($routerRules->toArray() as $key => $value){
        $router->add($key,$value);

    }

    return $router;
});


/**
 * 引入数据库配置信息
 * 根据环境变量获取对应的数据库配置
 */
$di->setShared('database_config', function () {
    return include_once APP_PATH . "/config/database.php";

});
/**
 * 引入版本信息
 * 根据环境变量获取对应的数据库配置
 */
$di->setShared('version_config', function () {
    return include_once APP_PATH . "/config/env.php";

});

/**
 * 注册全局公用配置
 * 根据环境变量获取对应配置信息
 */
$di->setShared('commonConfig', function () {
    return include_once APP_PATH . "/config/common.php";
});


/**
 * DI注册system配置
 */
$di -> setShared('systemConfig', function(){
   // return $config;
     return include_once APP_PATH . "/config/system.php";
});


/**
 * 注册支付回调逻辑方法
 * 用户支付处理的逻辑
 */
$di->setShared('pay_service', function () {
    return new \Services\PayService();
});
/**
 * DI注册日志服务
 */
$di -> setShared('logger', function() use($di){
    $day = date('Ymd');
    $logger = new \Marser\App\Core\PhalBaseLogger(BASE_PATH . "/app/cache/logs/{$day}.log");
    return $logger;
});

/**
 * 开启redis缓存服务
 */
$di->set('redis_cache', function () {
    $frontCache = new FrontendData(['lifetime' => 86400,]);
    $redisConfig = $this->get('database_config');
    $cache = new Redis($frontCache, [
        "host" => $redisConfig->redis->host,
        "port" => $$redisConfig->redis->port,
        "auth" => $redisConfig->redis->auth,
        "persistent" => $redisConfig->redis->persistent,
        "index" => $redisConfig->redis->index,
    ]);
    return $cache;
});

/**
 * 注册事件分发器
 * 404页面转发
 * 可以控制器中调用，一个控制器转发到另一个控制器
 */
$di->setShared('dispatcher', function () {
    $eventsManager = new EventsManager();
    $eventsManager->attach("dispatch", function ($event, $dispatcher, $exception) {
        if ($event->getType() == 'beforeNotFoundAction') {
            $dispatcher->forward(array(
                'controller' => 'base',
                'action' => 'route404',
                'namespace' => 'Controllers',
            ));
            return false;
        }
    });
    $dispatcher = new MvcDispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
});

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include_once APP_PATH . "/config/config.php";
});


/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->get('database_config');
    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'port' => $config->database->port,
        'charset' => $config->database->charset,
        'prefix' => $config->database->prefix
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }
    $eventsManager = new \Phalcon\Events\Manager();
    $profiler = new DbProfiler();
    $eventsManager -> attach('db', function ($event, $connection) use ($profiler) {
        if($event -> getType() == 'beforeQuery'){
            //在sql发送到数据库前启动分析
            $profiler -> startProfile($connection -> getSQLStatement());
        }
        if($event -> getType() == 'afterQuery'){
            //在sql执行完毕后停止分析
            $profiler -> stopProfile();
            //获取分析结果
            $profile = $profiler -> getLastProfile();
            $sql = $profile->getSQLStatement();
            $executeTime = $profile->getTotalElapsedSeconds();
            //日志记录
            $logger = $this->get('logger');
            $logger -> write_log("{$sql}  {$executeTime}", 'debug');
        }
    });

    $connection = new $class($params);
    /* 注册监听事件 */
    $connection->setEventsManager($eventsManager);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * DI注册自定义验证器
 */
$di -> setShared('validator', function() use($di){
    $validator = new \Marser\App\Library\Validator($di);
    return $validator;
});


/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $config = $this->get('database_config');
    $class = '\Phalcon\Session\Adapter\\' . $config->session->adapter;
    if($config->session->adapter=="Redis"){
        $session = new $class(array(
            'uniqueId' => $config->session->uniqueId,
            'host' => $config->redis->host,
            'port' => $config->redis->port,
            'auth' => $config->redis->auth,
            'persistent' => $config->redis->persistent,
            'lifetime' => $config->session->lifetime,
            'prefix' => $config->session->prefix,
            'index' => $config->session->index
        ));
    }
    else{
        $session = new $class();
    }

    $session->start();
    return $session;
});


/**
 * DI注册缓存服务
 */
$di -> setShared('cache', function(){
    $config = $this->getConfig();

    return new \Phalcon\Cache\Backend\File(new \Phalcon\Cache\Frontend\Output(), array(
        'cacheDir' => $config->application->cacheDir
    ));
});

/**
 * DI注册过滤器
 */
$di -> setShared('filter', function() use($di){
    $filter = new \Marser\App\Core\PhalBaseFilter($di);
    $filter -> init();
    return $filter;
});
