<?php

namespace common\models;

use Yii;
use linslin\yii2\curl;

class BaiDuDoc
{
    const BAIDU_DOC_HOST = "doc.bj.baidubce.com";
    const BAIDU_API_VERSION = "V2";
    const BAIDU_DOC_INFO_URI = "/v2/document";

    public static $doc_status_arr = [
        0 => 'UPLOADING',
        1 => 'PROCESSING',
        2 => 'PUBLISHED',
        3 => 'FAILED'
    ];

    private $auth_v = "bce-auth-v2";
    private $expire = 1800;
    private $ak;
    private $sk;
    private $sign;
    private $signature;
    private $signHeader;
    private $date;
    private $method;
    private $uri;
    private $url;
    private $canonicalURI;
    private $queryString;
    private $canonicalQueryString;
    private $header;
    private $canonicalHeaders;
    private $canonicalRequest;
    private $authStrPrefix;
    private $signingKey;

    public function __construct($config)
    {
        $this->ak = $config['ac'];
        $this->sk = $config['sc'];
        $this->getTime();
    }

    public function _init()
    {
        sort($this->header);
    }

    //注册
    public function register()
    {
        $this->method = "POST";
        $this->uri = self::BAIDU_DOC_INFO_URI."?register";
        $this->queryString = '';
        $header = [
            'host: '.self::BAIDU_DOC_HOST,
            'x-bce-date: '.$this->date,
            'content-type: application/json',
            //'x-bce-request-id'  => '',
            //'authorization'     => $this->sign,
            //'content-length'    => '',
        ];

        $this->header = $header;
        $this->_init();

        $header['authorization']  = $this->getSign();
        $this->url = '';
        $ret = $this->post($this->method,[],$header);

        //...
        print_r($ret);

    }

    //列表
    public function status($status = 'PUBLISHED')
    {

        $this->method = "POST";
        $this->uri = self::BAIDU_DOC_INFO_URI."?register";
        $this->queryString = '';
        $header = [
            'host: '.self::BAIDU_DOC_HOST,
            'x-bce-date: '.$this->date,
            'content-type: application/json',
            //'x-bce-request-id'  => '',
            //'authorization'     => $this->sign,
            //'content-length'    => '',
        ];

        $this->header = $header;
        $this->_init();

        $header['authorization']  = $this->getSign();
        $this->url = '';
        $ret = $this->post($this->method,[],$header);

        //...
        print_r($ret);

    }


    public function getSign()
    {
        if (!empty($this->sign)) return $this->sign;

        if (!empty($this->signature)) {
            $this->sign = $this->getAuthStrPrefix()."/".$this->getSignHeader()."/".$this->getSignature();
        }
        return $this->sign;
    }

    public function getSignature()
    {
        if (!empty($this->signature)) return $this->signature;

        if (!empty($this->ak) && !empty($this->sk)) {
            $this->signature = hash_hmac('sha256', $this->getCanonicalRequest(),$this->getSigningKey());
        }
        return $this->signature;
    }

    public function getCanonicalRequest()
    {
        if (!empty($this->canonicalRequest)) return $this->canonicalRequest;

        if (!empty($this->method)) {
            $arr = [
                $this->method,
                $this->getCanonicalURI(),
                $this->getCanonicalQueryString(),
                $this->getCanonicalHeaders()
            ];
            $this->canonicalRequest = implode("\n", $arr);
        }
        return $this->canonicalRequest;
    }

    public function getSigningKey()
    {
        if (!empty($this->signingKey)) return $this->signingKey;

        if (!empty($this->authStrPrefix) && !empty($this->sk)) {
            $this->authStrPrefix = hash_hmac('sha256',$this->getAuthStrPrefix(), $this->sk);
        }
        return $this->signingKey;
    }

    public function getAuthStrPrefix()
    {
        if (!empty($this->authStrPrefix)) return $this->authStrPrefix;

        if (!empty($this->ak) && !empty($this->sk)) {
            $this->authStrPrefix = $this->auth_v."/".$this->ak."/".$this->date."/".$this->expire;
        }
        return $this->authStrPrefix;
    }

    public function getCanonicalHeaders()
    {
        if (!empty($this->canonicalHeaders)) return $this->canonicalHeaders;

        if (!empty($this->header)) {
            $encode_arr = [];
            foreach($this->header as $v) {
                $str_arr = explode(":", $v);
                $str_k = urlencode(strtolower(trim($str_arr[0])));
                $str_v = isset($str_arr[1]) ? urlencode(trim($str_arr[1])) : '';
                if (!empty($str_v)) {
                    $encode_arr[] = trim($str_k.":".$str_v);
                }
            }

            $this->canonicalHeaders = $encode_arr;
        }
        return $this->canonicalHeaders;
    }

    public function getCanonicalQueryString()
    {
        if (!empty($this->canonicalQueryString)) return $this->canonicalQueryString;

        if (!empty($this->canonicalQueryString)) {
            $str = explode("&", $this->canonicalQueryString);
            $encode_arr = [];
            foreach($str as $v) {
                $str_arr = explode("=", $v);
                $str_k = urlencode(trim($str_arr[0]));
                $str_v = isset($str_arr[1]) ? urlencode(trim($str_arr[1])) : '';

                $encode_arr[] = trim($str_k."=".$str_v, "=");
            }

            $this->canonicalQueryString = implode("&", $encode_arr);
        }
        return $this->canonicalQueryString;
    }

    public function getCanonicalURI()
    {
        if (!empty($this->canonicalURI)) return $this->canonicalURI;

        if (!empty($this->uri)) {
            $uri = explode("?", $this->uri);
            $uri = $uri[0];
            $uri = urlencode($uri);
            $this->canonicalURI = str_ireplace('%2F', '/', $uri);
        } else {
            $this->canonicalURI = "/";
        }
        return $this->canonicalURI;
    }

    public function getSignHeader()
    {
        if (!empty($this->signHeader)) return $this->signHeader;

        if (!empty($this->header) && is_array($this->header)) {
            $tem = '';
            foreach($this->header as $v) {
                $ht = explode(":", $v);
                $ht = $ht[0];
                $tem .= ";".$ht;
            }
            $tem = trim($tem, ";");
            $this->signHeader = strtolower($tem);
        }
        return $this->signHeader;
    }

    public function getTime()
    {
        if (!empty($this->date)) return $this->date;
        $date_str = date(DATE_ATOM,time());
        return $this->date = substr($date_str,0,strpos($date_str,"+"))."Z";
    }

    public function post($url, array $post_data = array(), array $headers = array())
    {
        return $this->_post($url,$post_data,$headers);
    }

    private function _post($url, array $post_data = array(), array $headers = array())
    {
        $curl  = new curl\Curl();

        if (!empty($post_data)) {
            $url = $url->setPostParams($post_data);
        }

        if ( !empty($headers) ) {
            $url = $curl->setHeaders($headers);
        }
        return $curl->post($url);
    }
}
