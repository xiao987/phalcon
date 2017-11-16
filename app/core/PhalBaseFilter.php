<?php

/**
 * phalcon扩展过滤器
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Core;

use \Phalcon\Filter;

class PhalBaseFilter extends Filter{

    /**
     * 自定义初始化函数
     */
    public function init(){
        /** 添加remove_xss过滤器 */
        $this -> add('remove_xss', function($value){
            return \Marser\App\Library\Filter::remove_xss($value);
        });
    }

}
