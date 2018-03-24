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
     * @var array Option values that are passed through to the html <select> entity as attributes.
     */

    public $fieldOptions       = [];

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
     * @var array An associative array of your initial dropdown options.  You can populate this as a minimum for a
     * functioning Select2 widget.
     */

    public $list                = [];
    public $idField             = 'id';
    public $labelField          = 'text';

    /**
     * @inheritdoc
     */
    public function run()
    {

        Select2Asset::register($this->view);

        $script = "$(\"#{$this->options['id']}\").select2({$this->getOptions()});";
        $this->view->registerJs($script);
        echo Html::tag('div',
        $this->renderField(),
    ['class' => 'form-group']);

    }
    
    protected function renderfield(){

        return Html::activeDropDownList(
            $this->model,
            $this->attribute,
            $this->list,
            $this->getFieldOptions()
        );
    }

    protected function getFieldOptions(){
        return ArrayHelper::merge([
                'id'    => $this->options['id'],
                'class' =>'form-control',
            ],
            $this->fieldOptions
        );
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
            'escapeMarkup'          => new JsExpression($this->escapeMarkup),
            'dropdownAutoWidth'     => 'true',
        ],
            $this->url != null ? ['ajax' => $this->getAjaxParams()] : [],
            $this->pluginOptions);

        return Json::encode($options);
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

}
