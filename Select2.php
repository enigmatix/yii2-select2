<?php
namespace enigmatix\select2;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class Select2
 * @package enigmatix\widgets
 * @author Joel Small
 * @email joel.small@biscon.com.au
 *
 * This class applies the popular select2 widget code to a Yii2 ActiveField.  It supports full pass-through of options to
 * the Select2 library, so you can use the original library to it's full potential without being prevented by needing
 * duplicated support within this class.
 *
 * @property array  $ajaxParams
 * @property string $resultQuery
 * @property string $displayValue
 */
class Select2 extends InputWidget
{
    /**
     * @var string The query url you can send search terms to in order to filter your dropdown list dynamically.
     */

    public $url;

    /**
     * @var array Option values that are passed through to the .select2() javascript call.
     */

    public $pluginOptions       = [];

    /**
     * @var string Data label for the currently selected value.
     */

    public $label;

    /**
     * @var string Where there is no selected value, the text that appears as a placeholder in the widget.
     */

    public $placeholder         = "Search";

    /**
     * @var string A function that can be customised if required to manually escape the returned results.
     */

    public $escapeMarkup        = 'function (m) { return m; }';

    /**
     * @var string For complex forms, you can ascribe a prefix, if required, to the value being stored in the field.
     * This can help when needing to differentiate two otherwise identical fields.
     */

    public $valuePrefix         = '';

    /**
     * @var array An associative array of your initial dropdown options.  You can populate this as a minimum for a
     * functioning Select2 widget.
     */

    public $list                = [];
    public $idField = 'id';
    public $labelField = 'text';

    /**
     * @inheritdoc
     */
    public function run()
    {

        Select2Asset::register($this->view);
        $label      = $this->getLabel($this->displayValue);
        $valueList  = ArrayHelper::merge(
            $this->list,
            [$this->displayValue => $label]);

        echo Html::activeDropDownList(
            $this->model, 
            $this->attribute,
            $valueList,
            [
                'id'    => $this->options['id'],
                'class' =>'form-control',
                'value' => $this->displayValue,
            ]
        );

        $script = "$(\"#{$this->options['id']}\").select2({$this->getOptions()});";
        $this->view->registerJs($script);

        $this->label = $this->getDisplayValue();

    }

    protected function getDisplayValue(){

        if($this->label != null)
            return $this->label;

        $fieldName = $this->fieldName;

        /* Early exit for situations where the field is actually an array.  This widget cannot interpret any non-string value */
        if($this->attribute != $fieldName)
            return null;

        $value = $this->model->$fieldName;

        if(is_array($value)){
            throw new InvalidConfigException("$fieldName must be a string, array found");
        }
        return $value;

    }

    public function getFieldName(){
        return        Html::getAttributeName($this->attribute);
    }

    /**
     * Prepares the options array to be turned into JSON further along the execution pathway.  Any function declarations
     * must be wrapped in a JsExpression class so that the Yii2 \yii\helpers\json::encode() method can correctly escape them
     * You can override all values within the defined defaults here, by supplying the relevant array structure to pluginOptions
     *
     * @return string
     */

    protected function getOptions()
    {
        $options = ArrayHelper::merge([
            'placeholder'           => $this->placeholder,
            'ajax'                  => $this->ajaxParams,
            'escapeMarkup'          => new JsExpression($this->escapeMarkup),
            'dropdownAutoWidth'     => 'true'
        ],$this->pluginOptions);

        return Json::encode($options);
    }

    /**
     * Looks for a label for the current data value, in the order of:
     *
     * 1. manually specified label
     * 2. data label as per list array
     * 3. a humanized version of the data value
     *
     * @param string $value
     * @return mixed|string
     */

    protected function getLabel($value){
        if($this->label == null){
            $label      = ArrayHelper::getValue($this->list, $value);
        }else{
            $label      = $this->label;
        }
        return $label == null ? Inflector::humanize($value) : $label;

    }

    /**
     * If supplying your dataset by ajax, this provides a standard interface to fetch, parse and display the data.  By
     * default, it sends the request to the url you provide using the search term ?q=searchterm
     *
     * @return array
     */
    protected function getAjaxParams()
    {
        return [
            'url'           => $this->url,
            'dataType'      => 'json',
        ];
    }

    /**
     * Reguired by the getAjaxParams() method, this method identifies exactly how the result is parsed.  If you need to
     * apply a prefix to each value, this can be done by supplying a 'valuePrefix' when declaring the widget.
     *
     * @return JsExpression
     */

    protected function getResultQuery()
    {
        if($this->valuePrefix == ''){
            $string = ' function (data, page) {return {results: data.results};}';
        }else{
            $string = 'data.results.map(function(item){return item["id"] = "'. $this->valuePrefix . '" + item["id"];});';
            $string = "function (data, page) {".$string." return {results: data.results};}";
        }
        return $string;
    }

}
