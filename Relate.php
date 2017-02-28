<?php
/**
 * Created by PhpStorm.
 * User: Joel Small
 * Date: 29/12/14
 * Time: 6:30 PM
 */
namespace enigmatix\select2;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\helpers\Inflector;


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

    /**
     * @var Model
     */
    protected $fieldModel;

    public function init() {
        parent::init();

        if($this->fieldModel == null)
            $this->fieldModel = $this->getFieldModel();

        if($this->value == null)
            $this->value        = $this->fieldModel->id;

        if($this->list == null)
            $this->list         = [$this->value => $this->getLabel()];

        if($this->url == null)
            $this->url = $this->generateUrl();

    }


    protected function getLabel() {

        return $this->fieldModel->name;

    }

    protected function getController(){


        if($this->controller != null){
            return $this->controller;
        }

        $field      = $this->getFieldModel();
        if(method_exists($field, 'getController')){

            return $field->getController();
        }else{
            $className = StringHelper::basename($field::className());

            return Inflector::slug(Inflector::titleize($className));
        }
    }

    protected function generateUrl(){
            return  Url::to(['/'.$this->getController(), 'json' => '']);
    }

    protected function getFieldModel(){

        if($this->fieldModel != null)
            return $this->fieldModel;

        $fieldName  = $this->getFieldName();
        $field      = rtrim(str_replace('id', '', $fieldName), '_');

        $activeQueryName = 'get' . Inflector::camelize($field);

        if(!method_exists($this->model, $activeQueryName))
            throw new InvalidConfigException("fieldModel not supplied in config, and default method $activeQueryName "
                . "does not exist in " . $this->model->className());

        if($this->model->$fieldName != null){

            $propertyName   = Inflector::variablize($field);

            return $this->model->$propertyName;

        } else {

            /* @var \yii\db\ActiveQuery $activeQuery */

            $activeQuery            = $model->$activeQueryName();
            $fieldModelClassName    = $activeQuery->modelClass;

            return new $fieldModelClassName;
        }



    }

}
