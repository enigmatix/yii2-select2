<?php
/**
 * Created by PhpStorm.
 * User: Joel Small
 * Date: 29/12/14
 * Time: 6:30 PM
 */
namespace enigmatix\select2;

use yii\base\Model;
use yii\helpers\StringHelper;
use yii\helpers\Url;



/**
 * Class Relate
 * @package enigmatix\widgets
 * @author Joel Small
 * @email joel.small@biscon.com.au
 *
 * This class builds  the Select2 widget, programattically defining the values required to build a relate field
 */
class Relate extends Select2
{
    public $controller;
    public function run()
    {
        if($this->url == null)
            $this->url = $this->generateUrl();

        parent::run();
    }

    public function retrieveValue($fieldName){
        $value = parent::retrieveValue($fieldName);
        return $this->valuePrefix . $value;
    }

    protected function getController(){
        $className = StringHelper::basename($model::className());
        return strtolower($className);
    }

    protected function generateUrl(){

        $model      = $this->model;
        $controller = method_exists($model, 'getController') ? $model->getController() : strtolower($this->getController());

        return Url::to([$controller, 'json' => null]);

    }
}
