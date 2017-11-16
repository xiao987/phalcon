<?php

/**
 * 业务仓库工厂
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Backend\Repositories;

class RepositoryFactory{

    /**
     * 仓库对象容器
     * @var array
     */
    private static $_repositories = array();

    /**
     * 获取仓库对象
     * @param $repositoryName
     * @return object
     * @throws \Exception
     */
    public static function get_repository($repositoryName){
        $repositoryName = __NAMESPACE__ . "\\" . ucfirst($repositoryName);
        if(!class_exists($repositoryName)){
            throw new \Exception("{$repositoryName}类不存在");
        }
        if(!isset(self::$_repositories[$repositoryName]) || empty(self::$_repositories[$repositoryName])){
            self::$_repositories[$repositoryName] = new $repositoryName();
        }
        return self::$_repositories[$repositoryName];
    }
}
