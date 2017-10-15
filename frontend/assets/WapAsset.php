<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class WapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css',
        'http://cdn.bootcss.com/font-awesome/4.3.0/css/font-awesome.min.css',
        'https://cdn.bootcss.com/formvalidation/0.6.1/css/formValidation.min.css',
        '/css/wap.css',
    ];
    public $js = [
        'http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js',
        'https://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js',
        'https://cdn.bootcss.com/formvalidation/0.6.1/js/formValidation.min.js',
        'https://cdn.bootcss.com/formvalidation/0.6.1/js/framework/bootstrap.min.js',
    ];
}
