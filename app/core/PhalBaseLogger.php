<?php

/**
 * Phalcon日志扩展
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Core;

class PhalBaseLogger extends \Phalcon\Logger\Adapter\File{

    /**
     * 日志记录
     * @param $log
     * @param $level 日志等级
     * @link https://docs.phalconphp.com/zh/latest/reference/logging.html
     */
    public function write_log($log, $level='error'){
        is_array($log) && $log = json_encode($log);

        empty($level) && $level = 'error';
        $level = strtolower($level);
        $this -> $level($log);
    }
}