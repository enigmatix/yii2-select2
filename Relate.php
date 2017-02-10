<?php
/**
 * Created by PhpStorm.
 * User: Joel Small
 * Date: 29/12/14
 * Time: 6:30 PM
 */
namespace enigmatix\select2;

use yii\base\InvalidConfigException;
use yii\base\Model;
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
    public $fieldModel;
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

        if($this->controller != null){
            return $this->controller;
        }

        $field      = $this->fieldModel != null ?: $this->getFieldModel();
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
        $model = $this->model;
        $field = str_replace('_id', '', $this->getFieldName());

        $activeQueryName = 'get' . Inflector::camelize($field);
        if(!method_exists($model, $activeQueryName)){
            $activeQueryName .= 's';
            if(!method_exists($model, $activeQueryName))
                throw new InvalidConfigException("No controller string, url string or fieldModel supplied in widget config, and default method $activeQueryName does not exist in " . $this->model->className());
        }


        /* @var \yii\db\ActiveQuery $activeQuery */

        $activeQuery            = $model->$activeQueryName();
        $fieldModelClassName    = $activeQuery->modelClass;

        if($this->value != null){
            return $fieldModelClassName::findOne($this->value);
        } else {

            return new $fieldModelClassName;
        }

    }

}
