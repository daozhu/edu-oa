<?php

namespace common\models;

use Yii;
use linslin\yii2\curl;

class BaiDuDoc
{
    const BAIDU_DOC_HOST = "doc.bj.baidubce.com";
    const BAIDU_DOC_INFO_URI = "/v2/document/";

    private $ac;
    private $sk;
    private $sign;

    public function __construct()
    {
        $this->sign = '';
    }


    public function register()
    {
        $headers = [
            'host'              => self::BAIDU_DOC_HOST,
            //'x-bce-date'        => '',
            //'x-bce-request-id'  => '',
            'authorization'     => $this->sign,
            'content-type'      => 'application/json',
            //'content-length'    => '',
        ];

        $method = "?register";
        $ret = $this->post($method,[],$headers);

        //...
        print_r($ret);

    }

    //url
    public function getBaiduDocInfo()
    {
        if ($this->sys != 1 || empty($this->file)) return null;

        $url   = self::BAIDU_DOC_HOST.self::BAIDU_DOC_INFO_URI.$this->file;


    }


    public function post($url, array $post_data = array(), array $headers = array())
    {
        $ret = $this->_post($url,$post_data,$headers);
    }

    private function _post($url, array $post_data = array(), array $headers = array())
    {
        $curl  = new curl\Curl();

        if (!empty($post_data)) {
            $url->setPostParams($post_data);
        }

        if ( !empty($headers) ) {
            $curl->setHeaders($headers);
        }
        return $curl->post($url);
    }
}
