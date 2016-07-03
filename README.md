# yii2-select2
Yii2 wrapper for the Select2 javascript widget.

This library makes the popular Select2 widget available for easy use in 
Yii2 projects within an ActiveForm.  Please note that you cannot 
currently use this library to create a standalone Select2 widget without 
using an ActiveForm.

This widget is intentionally light on proprietary code, and instead 
favours existing Yii2 objects, classes and helpers where they are 
provided.  It also provides a passthru mechanism so you can use the full 
Select2 range of options without having to rely on support being 
manually added to this php widget.

REQUIREMENTS
------------

The minimum requirement by this library is:
1. your Web server supports PHP 5.4.0
2. you have Yii2 installed
3. You have correctly configured the composer fxp plugin as per the 
original Yii2 installation instructions

INSTALLATION
------------

* `composer require enigmatix/yii2-select2`

GETTING STARTED
---------------

`
use enigmatix/yii2select/Select2;

$dropdownList = ['Yes' => 'Yes', 'No'];

 <?= $form->field($model, 'primary_tag')->widget(Select2::className(), 
 ['list' => $dropdownList]) ?>`

ADVANCED USAGE
--------------

If you need to pass javascript expressions (functions or anything more 
complex than a string you will need to use an instance of 
yii\web\JsExpression to ensure the resulting config is encoded correctly.
See http://www.yiiframework.com/doc-2.0/yii-web-jsexpression.html for 
more details: eg

`
$ajaxDataFunction = new JsExpression("function (term, page) {return {q: term,};}")

<?= $form->field($model, 'primary_tag')->widget(Select2::className(), 
 [
    'list' => $dropdownList
    'pluginOptions => [
        'ajax' => [
            'data => $ajaxDataFunction,            
       ]
 ]) ?>`

`

USING NATIVE SELECT2 FEATURES
-----------------------------

As per the original Select2 docs found here: https://select2.github.io/
you can implement any of these features via the pluginOptions array.  The
array you build is passed through and JSON encoded as is, and will 
override any defaults within the widget itself.

CONTRIBUTIONS
-------------

If you would like to contribute to this codebase, please make a pull request or report an issue.  The types of
contributions that will be most useful are:

1. adding php abstractions for new features
2. refining the ajax support (not currently supported)
3. coding practice feedback and better customisability and usability

But of course all feedback is welcome :).