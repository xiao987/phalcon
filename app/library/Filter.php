<?php

/**
 * 过滤器
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Library;

class Filter {

    /**
     * 清除xss特殊字符
     * @param $str
     * @return mixed
     */
    public static function remove_xss($str){
        $str = filter_var(trim($str), FILTER_SANITIZE_STRING);
        return $str;
    }
}