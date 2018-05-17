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

    private $auth_v = "bce-auth-v1";
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

    public $PERCENT_ENCODED_STRINGS;

    public function __construct($config)
    {
        $this->ak = $config['ak'];
        $this->sk = $config['sk'];
        $this->getTime();
        $this->encode_str();
    }

    public function _init()
    {
        sort($this->header);
    }

    //发布
    public function publish($data,$doc_id = '')
    {
        $this->method = "PUT";
        $this->uri = self::BAIDU_DOC_INFO_URI."/{$doc_id}"."?";
        $this->queryString = 'publish';
        $this->header = [
            'host: '.self::BAIDU_DOC_HOST,
            "x-bce-date: ".$this->getTime(),
            "Content-Type: application/json",
        ];
        $header = [
            'host'          => self::BAIDU_DOC_HOST,
            "x-bce-date"    => $this->getTime(),
            "Content-Type"  => "application/json",
        ];
        $this->_init();
        $header['authorization']  = $this->getSign();

        $this->url = self::BAIDU_DOC_HOST.$this->uri.$this->queryString;
        return $this->_put($this->url,$data,$header);
    }


    //注册
    public function register($data = [], $querystr = '')
    {
        $this->method = "POST";
        $this->uri = self::BAIDU_DOC_INFO_URI."?";
        $this->queryString = 'register'. $querystr;
        $this->header = [
            'host: '.self::BAIDU_DOC_HOST,
            "x-bce-date: ".$this->getTime(),
            "Content-Type: application/json",
        ];
        $header = [
            'host'          => self::BAIDU_DOC_HOST,
            "x-bce-date"    => $this->getTime(),
            "Content-Type"  => "application/json",
        ];
        $this->_init();
        $header['authorization']  = $this->getSign();

        $this->url = self::BAIDU_DOC_HOST.$this->uri.$this->queryString;
        return $this->post($this->url,$data,$header);
    }

    // 从 BOS 导入
    public function source($query = '', $data = [])
    {
        $this->method = "POST";
        $this->uri = self::BAIDU_DOC_INFO_URI."?";
        $this->queryString = $query;
        $this->header = [
            'host: '.self::BAIDU_DOC_HOST,
            "x-bce-date: ".$this->getTime(),
            "Content-Type: application/json",
        ];
        $header = [
            'host' => self::BAIDU_DOC_HOST,
            "x-bce-date"    => $this->getTime(),
            "Content-Type"  => "application/json",
        ];
        $this->_init();

        $header['authorization'] = $this->getSign();
        $post_data = $data;
        $this->url = self::BAIDU_DOC_HOST.$this->uri.$this->queryString;
        return $this->post($this->url,$post_data,$header);
    }

    //列表
    public function search($data, $doc_id = '')
    {
        $this->method = "GET";
        $this->uri = self::BAIDU_DOC_INFO_URI."/{$doc_id}";
        $this->queryString = '';
        $this->header = [
            'host: '.self::BAIDU_DOC_HOST,
            //'x-bce-date: '.$this->date,
            //'content-type: application/json',
            //'x-bce-request-id'  => '',
            //'authorization'     => $this->sign,
            //'content-length'    => '',
        ];

        $header = [
            'host' => self::BAIDU_DOC_HOST,
        ];
        $this->_init();

        $header['authorization']  = $this->getSign();
        $this->url = self::BAIDU_DOC_HOST.$this->uri.$this->queryString;
        return $this->_get($this->url,[],$header);
    }

    //列表
    public function status($query = '')
    {

        $this->method = "GET";
        $this->uri = self::BAIDU_DOC_INFO_URI."/"."?";
        $this->queryString = $query;
        $this->header = [
            'host: '.self::BAIDU_DOC_HOST,
            //'x-bce-date: '.$this->date,
            //'content-type: application/json',
        ];

        $header = [
            'host' => self::BAIDU_DOC_HOST,
        ];
        $this->_init();

        $header['authorization']  = $this->getSign();
        $this->url = self::BAIDU_DOC_HOST.$this->uri.$this->queryString;
        return $this->_get($this->url,[],$header);
    }

    public function getSign()
    {
        //if (!empty($this->sign)) return $this->sign;

        if (!empty($this->getSignature())) {
            $this->sign = $this->getAuthStrPrefix()."/".$this->getSignHeader()."/".$this->getSignature();
        }
        return $this->sign;
    }

    public function getSignature()
    {
        //if (!empty($this->signature)) return $this->signature;

        if (!empty($this->ak) && !empty($this->sk)) {
            $this->signature = hash_hmac('sha256', $this->getCanonicalRequest(),$this->getSigningKey());
        }
        return $this->signature;
    }

    public function getCanonicalRequest()
    {
        //if (!empty($this->canonicalRequest)) return $this->canonicalRequest;

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
        //if (!empty($this->signingKey)) return $this->signingKey;

        if (!empty($this->getAuthStrPrefix()) && !empty($this->sk)) {
            $this->signingKey = hash_hmac('sha256',$this->getAuthStrPrefix(), $this->sk);
        }
        return $this->signingKey;
    }

    public function getAuthStrPrefix()
    {
        //if (!empty($this->authStrPrefix)) return $this->authStrPrefix;

        if (!empty($this->ak) && !empty($this->sk)) {
            $this->authStrPrefix = $this->auth_v."/".$this->ak."/".$this->date."/".$this->expire;
        }
        return $this->authStrPrefix;
    }

    public function getCanonicalHeaders()
    {
        //if (!empty($this->canonicalHeaders)) return $this->canonicalHeaders;

        if (!empty($this->header)) {
            $encode_arr = [];
            foreach($this->header as $v) {
                list($str_1,$str_2) = explode(":", $v, 2);
                $str_k = $this->urlEncode(strtolower(trim($str_1)));
                $str_v = !empty($str_2) ? $this->urlEncode(trim($str_2)) : '';
                if (!empty($str_v)) {
                    $encode_arr[] = trim($str_k.":".$str_v);
                }
            }
            sort($encode_arr);
            $this->canonicalHeaders = implode("\n", $encode_arr);
        }
        return $this->canonicalHeaders;
    }

    public function getCanonicalQueryString()
    {
        //if (!empty($this->canonicalQueryString)) return $this->canonicalQueryString;

        if (!empty($this->queryString)) {
            $str = explode("&", $this->queryString);
            $encode_arr = [];
            foreach($str as $v) {
                list($str_1,$str_2) = explode("=", $v, 2);
                $str_k = $this->urlEncode(trim($str_1));
                $str_v = !empty($str_2) ? $this->urlEncode(trim($str_2)) : '';

                $encode_arr[] = trim($str_k."=".$str_v);
            }
            sort($encode_arr);
            $this->canonicalQueryString = implode("&", $encode_arr);
        }
        return $this->canonicalQueryString;
    }

    public function getCanonicalURI()
    {
        //if (!empty($this->canonicalURI)) return $this->canonicalURI;

        if (!empty($this->uri)) {
            list($uri,$query_str) = explode("?", $this->uri, 2);
            $this->canonicalURI   = $this->urlEncodeExceptSlash($uri);
        } else {
            $this->canonicalURI = "/";
        }
        return $this->canonicalURI;
    }

    public function getSignHeader()
    {
        //if (!empty($this->signHeader)) return $this->signHeader;

        if (!empty($this->header) && is_array($this->header)) {
            $tem = [];
            foreach($this->header as $v) {
                list($ht, $ht_2)= explode(":", $v, 2);
                $tem[] = $ht;
            }
            sort($tem);
            $this->signHeader = strtolower(trim(implode(";", $tem)));
        }
        return $this->signHeader;
    }

    public function getTime()
    {
        //if (!empty($this->date)) return $this->date;
        return $this->date = date('Y-m-d\TH:i:s\Z');
    }

    public function encode_str()
    {
        $this->PERCENT_ENCODED_STRINGS = array();
        for ($i = 0; $i < 256; ++$i) {
            $this->PERCENT_ENCODED_STRINGS[$i] = sprintf("%%%02X", $i);
        }
        foreach (range('a', 'z') as $ch) {
            $this->PERCENT_ENCODED_STRINGS[ord($ch)] = $ch;
        }

        foreach (range('A', 'Z') as $ch) {
            $this->PERCENT_ENCODED_STRINGS[ord($ch)] = $ch;
        }

        foreach (range('0', '9') as $ch) {
            $this->PERCENT_ENCODED_STRINGS[ord($ch)] = $ch;
        }
        $this->PERCENT_ENCODED_STRINGS[ord('-')] = '-';
        $this->PERCENT_ENCODED_STRINGS[ord('.')] = '.';
        $this->PERCENT_ENCODED_STRINGS[ord('_')] = '_';
        $this->PERCENT_ENCODED_STRINGS[ord('~')] = '~';
    }

    public function urlEncodeExceptSlash($path)
    {
        return str_replace("%2F", "/", $this->urlEncode($path));
    }

    public function urlEncode($value)
    {
        $result = '';
        for ($i = 0; $i < strlen($value); ++$i) {
            $result .= $this->PERCENT_ENCODED_STRINGS[ord($value[$i])];
        }
        return $result;
    }

    public function post($url, array $post_data = array(), array $headers = array())
    {
        return $this->_post($url,$post_data,$headers);
    }

    private function _put($url, array $post_data = array(), array $headers = array())
    {
        $curl_obj  = new curl\Curl();

        $curl_obj->setOption(CURLINFO_HEADER_OUT,1);

        if (!empty($post_data)) {
            $curl_obj = $curl_obj->setRawPostData(json_encode($post_data));
        }

        if ( !empty($headers) ) {
            $curl_obj = $curl_obj->setHeaders($headers);
        }

        $ret = $curl_obj->put($url);
        return $ret;
    }


    private function _post($url, array $post_data = array(), array $headers = array())
    {
        $curl_obj  = new curl\Curl();

        $curl_obj->setOption(CURLOPT_POST,1);
        $curl_obj->setOption(CURLINFO_HEADER_OUT,1);

        if (!empty($post_data)) {
            $curl_obj = $curl_obj->setRawPostData(json_encode($post_data));
        }

        if ( !empty($headers) ) {
            $curl_obj = $curl_obj->setHeaders($headers);
        }

        $ret = $curl_obj->post($url);
        return $ret;
    }
    private function _get($url, array $data = array(), array $headers = array())
    {
        $curl_obj  = new curl\Curl();

        $curl_obj->setOption(CURLINFO_HEADER_OUT,1);

        if (!empty($data)) {
            $curl_obj = $curl_obj->setGetParams($data);
        }

        if ( !empty($headers) ) {
            $curl_obj = $curl_obj->setHeaders($headers);
        }
        return $curl_obj->get($url);
    }
}
