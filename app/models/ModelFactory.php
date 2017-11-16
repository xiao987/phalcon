<?php

/**
 * 模型工厂
 * @category zgcdb0001
 * @copyright Copyright (c) 2016 zgcdb0001 team (http://www.kuaishangxian.com.cn)
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Models;

class ModelFactory {

    /**
     * 模型对象容器
     * @var array
     */
    private static $_models = array();

    /**
     * 获取模型对象
     * @param $modelName
     * @return object
     * @throws \Exception
     */
    public static function get_model($modelName){
        $modelName = __NAMESPACE__ . "\\" . ucfirst($modelName);
        if(!class_exists($modelName)){
            throw new \Exception("{$modelName}类不存在");
        }
        if(!isset(self::$_models[$modelName]) || empty(self::$_models[$modelName])){
            self::$_models[$modelName] = new $modelName();
        }
        return self::$_models[$modelName];
    }
}
