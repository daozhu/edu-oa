<?php
namespace common\models;

include __DIR__.'/../sdk/baidu/bce/BaiduBce.phar';

use BaiduBce\BceClientConfigOptions;
use BaiduBce\Util\Time;
use BaiduBce\Util\MimeTypes;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Services\Bos\BosClient;
use BaiduBce\Services\Bos\CannedAcl;
use BaiduBce\Services\Bos\BosOptions;
use BaiduBce\Auth\SignOptions;

class BaiBosClient
{
    private $client;
    private $bucket;
    private $obj_key;

    private $key;
    private $filename;
    private $download;

    public function __construct($config)
    {
        $this->client = new BosClient($config);
    }

    //简单文件上传
    public function upload($bucketName, $objectKey, $file)
    {
        return $this->client->putObjectFromFile($bucketName, $objectKey, $file);
    }
}
