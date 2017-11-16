<?php
/**
 * Created by PhpStorm.
 * User: Mr.Zhou
 * Date: 2017/7/20
 * Time: 上午11:33
 */
return new \Phalcon\Config( [
    'database' => [
        'adapter' => 'Mysql',
        'host' => '192.168.1.10',
        'username' => 'developer',
        'password' => 'developer',
        'dbname' => 'zgcdb0001',
        'port' => '3306',
        'charset' => 'utf8',
        'prefix' => ''
    ],
    'redis'=>[
        "host" => "192.168.1.10",
        "port" => 6379,
        "auth" => "",
        "persistent"=>false,
        "index" => 0,
        'lifetime' => 3600,
    ],
    'session'=>[
        "adapter"=>'Files',
        "uniqueId"=>'my-private-app',
        'lifetime' => 1440,
        'prefix' => '',
        'index' => 1,
    ],
]);