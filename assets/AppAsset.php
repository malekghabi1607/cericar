<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */


namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',   // ← TON CSS DOIT ÊTRE ICI
    ];

    public $js = [
        'js/main.js',     // si tu as des scripts
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',   // pour le responsive
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}