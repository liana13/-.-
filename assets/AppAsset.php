<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/daterangepicker.css',
        'css/site.css',
        'css/responsive.css',
        'css/font-awesome/css/font-awesome.min.css',
        'css/owl.carousel.min.css',
        'css/jquery.fancybox.min.css',
        'css/flexslider.css',
        'css/jquery-ui.min.css',
    ];
    public $js = [
        'js/moment.min.js',
        'js/daterangepicker.js',
        'js/owl.carousel.min.js',
        'js/jquery.flexslider.js',
        'js/jquery.fancybox.min.js',
        'js/jquery-ui.min.js',
        'js/site.js',
   ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
