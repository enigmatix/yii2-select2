<?php
/**
 * Created by PhpStorm.
 * User: Joel Small
 * Date: 13/07/2015
 * Time: 5:29 PM
 */

namespace enigmatix\yii2select;

use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/select2/dist';
    public $css = [
        'css/select2.min.css',
    ];
    public $js = [
        'js/select2.full.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}