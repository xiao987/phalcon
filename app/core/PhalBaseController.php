<?php

/**
 * Phalcon控制器扩展
 * @category ZGC001
 * @copyright Copyright (c) 2017 KSX zgc001
 * @license KSX License 2.0
 * @link www.kuaishangxian.com.cn
 * @author Marser
 *
 */

namespace Marser\App\Core;

class PhalBaseController extends \Phalcon\Mvc\Controller {
    /**
     *  ajax 请求状态码-成功
     */
    const CODE_SUCCESS = 200;
    /**
     * ajax 请求状态码-失败
     */
    const CODE_ERROR = 300;

    /**
     * ajax 请求状态码-404
     */
    const CODE_NO_FOUND = 404;
    /**
     * ajax 请求状态码-内部错误
     */
    const   CODE_ERROR_INTERNAL = 500;
    /**
     * ajax 请求错误状态描述-没有权限
     */
    const ERROR_CODE_NO_AUTH = 1001;

    /**
     * ajax 请求错误状态文字提醒列表
     */
    public static $error_msgs = array(
        self::ERROR_CODE_NO_AUTH => "没有权限",

    );
    public function initialize(){
        
    }

    /**
     * ajax输出
     * @param $message
     * @param int $code
     * @param array $data
     * @param int $error_code
     * @author Marser
     */
    protected function ajax_return($message, $code=200, array $data=array(),$error_code=""){
        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );
        if(!empty($error_code)){
            $result['error_code']=$error_code;
            $result['error_msg']=self::get_error_msg($error_code);
        }
        //$this -> response -> setContent(json_encode($result));
        $this -> response -> setJsonContent($result);
        $this -> response -> send();
        exit;
    }

    /**
     * exception日志记录
     * @param \Exception $e
     * @author Marser
     */
    protected function write_exception_log(\Exception $e){
        $logArray = array(
            'file' => $e -> getFile(),
            'line' => $e -> getLine(),
            'code' => $e -> getCode(),
            'message' => $e -> getMessage(),
            'trace' => $e -> getTraceAsString(),
        );
        $this -> logger -> write_log($logArray);
    }

    /**
     * 获取error_code对应的错误信息
     * @param int $error_code
     * @return string
     */
    protected static function get_error_msg($error_code){
        return isset(self::$error_msgs[$error_code])?self::$error_msgs[$error_code]: "未知错误";
    }
}