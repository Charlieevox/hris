<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets_b;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot/';
    public $baseUrl = '@web/assets_b';
    public $css = [
        'css/fonts/Roboto/roboto.css',
        'css/site.css',
        'css/fullcalendar.css',
        'css/fullcalendar.print.css',
        'themes/areas.css',
        'themes/month_white.css',
        'themes/month_green.css',
        'themes/month_transparent.css',
        'themes/month_traditional.css',
        'themes/navigator_8.css',
        'themes/navigator_white.css',
        'themes/calendar_transparent.css',
        'themes/calendar_white.css',
        'themes/calendar_green.css',
        'themes/calendar_traditional.css',
        'themes/scheduler_8.css',
        'themes/scheduler_white.css',
        'themes/scheduler_green.css',
        'themes/scheduler_blue.css',
        'themes/scheduler_traditional.css',
        'themes/scheduler_transparent.css'
               
    ];
    public $js = [
        'js/bootbox.min.js',
        'js/js_general.js',
    	'js/js_login.js',
        'js/jquery.maskMoney.js',
        'js/daypilot-all.min.js',
        'js/moment.min.js',
        'js/fullcalendar.min.js'
       
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
		'raoul2000\bootswatch\BootswatchAsset',
    	'yii\web\JqueryAsset',
        '\rmrevin\yii\fontawesome\AssetBundle',
        '\kartik\select2\Select2Asset'
    ];
}
