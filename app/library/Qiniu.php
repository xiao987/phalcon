<?php

/**
 * Created by PhpStorm.
 * User: zhoujianjun
 * Date: 2017/7/16
 * Time: 上午9:54
 */

namespace App\Library;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Phalcon\Di;


class Qiniu
{
    public $config;

    public function __construct()
    {
        $config = Di::getDefault()->get("commonConfig");
        $this->config = [
            'accessKey' => $config->qiniu->accessKey,
            'secretKey' => $config->qiniu->secretKey,
            'bucket' => $config->qiniu->bucket,
        ];
    }

    /**
     * 七牛存储授权认证
     * 返回Token信息
     * @return string
     */
    public function auth()
    {
        $auth = new Auth($this->config['accessKey'], $this->config['secretKey']);
        $token = $auth->uploadToken($this->config['bucket']);
        return $token ? $token : '';
    }

    /**
     * 上传文件
     * 返回文件地址
     */
    public function upload($fileName = '', $file = '')
    {
        $token = $this->auth();
        $uploadMgr = new UploadManager();
        $name = $fileName ? $fileName : date('YmdHis') . rand(1000, 9999);
        $file = $file ? $file : $_FILES['filedata'];
        list($ret, $err) = $uploadMgr->putFile($token, $name, $file['tmp_name']);
        if ($err !== null) {
            return '';
        } else {
            return $ret['key'];
        }
    }

    /**
     * 删除存储对象里面的文件
     * @param string $name
     * @return bool
     */
    public function delete($name='')
    {
        $auth = new Auth($this->config['accessKey'], $this->config['secretKey']);
        $bucketMgr = new BucketManager($auth);
        $err = $bucketMgr->delete($this->config['bucket'], $name);
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
    }
}
