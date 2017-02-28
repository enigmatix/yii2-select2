<?php
/**
 * Created by PhpStorm.
 * User: joels
 * Date: 28/02/2017
 * Time: 10:49 AM
 */

namespace enigmatix\select2;


use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\helpers\Inflector;



class ManyToMany extends Relate
{

    public function init() {

        if($this->list == null){

            $func       = 'get'. Inflector::camelize($this->name);
            $valuelist  =  call_user_func([$this->model, $func]);

            if(!$valuelist instanceof ActiveQuery)
                throw new InvalidConfigException('An instance of '  . ActiveQuery::className() . ' must be supplied to the '
                    . ' value parameter');

            $list =  $valuelist->all();

            $listValues = [];
            foreach ($list as $model)
                $listValues[$model->id] =  $model->name;

            $this->list     = $listValues;
            $this->value    = count($listValues) ? array_keys($listValues) : [];
        }

        $this->fieldModel = new $valuelist->modelClass;

        parent::init();


    }

    protected function getFieldOptions() {

        return ArrayHelper::merge(parent::getFieldOptions(), ['multiple' => true]);
    }

    protected function renderfield() {


        return Html::dropDownList(
            $this->name,
            $this->value,
            $this->list,
            $this->getFieldOptions()
        );

    }

    public function getFieldName(){
        return        Html::getAttributeName($this->name);
    }


}