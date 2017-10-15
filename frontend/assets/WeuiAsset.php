<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class WeuiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'weui/style/weui.css',
        'weui/style/weui2.css',
        'weui/style/weui3.css',
        'weui/zepto.min.js',
    ];
    public $js = [
        //'weui/zepto.min.js',
    ];
}
