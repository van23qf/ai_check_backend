<?php

namespace frontend\assets;

use yii\web\View;
use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position'=>View::POS_HEAD];
    public $css = [
        'css/site.css',
        'css/jasny-bootstrap.min.css',
        'css/webuploader.css',
        'css/upload.css',
    ];
    public $js = [
        'js/jasny-bootstrap.min.js',
        'js/webuploader.min.js',
        'js/upload.js',
//        'js/app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
