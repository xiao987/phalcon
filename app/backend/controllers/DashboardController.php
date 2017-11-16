<?php

/**
 * 控制面板
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 */

namespace Marser\App\Backend\Controllers;

use \Marser\App\Backend\Controllers\BaseController,
    \Marser\App\Library\ServerNeedle;

class DashboardController extends BaseController{

    public function initialize(){
        parent::initialize();
    }

    /**
     * 控制台页面
     */
    public function indexAction(){


        /** 获取服务器信息 */
        $systemInfo = array(
            'osName' => ServerNeedle::os_name(),
            'osVersion' => ServerNeedle::os_version(),
            'serverName' => ServerNeedle::server_host(),
            'serverIp' => ServerNeedle::server_ip(),
            'serverSoftware' => ServerNeedle::server_software(),
            'serverLanguage' => ServerNeedle::accept_language(),
            'serverPort' => ServerNeedle::server_port(),
            'phpVersion' => ServerNeedle::php_version(),
            'phpSapi' => ServerNeedle::php_sapi_name(),
        );
        $this -> view -> setVars(array(
            'appVersion' => $this -> systemConfig -> app -> version,
            'systemInfo' => $systemInfo,
        ));

        $this -> view -> pick('dashboard/index');
    }
}
