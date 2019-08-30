<h1 align="center">
    <a href="http://demos.krajee.com" title="Krajee Demos" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-summernote-samples/samples/krajee-logo-b.png" alt="Krajee Logo"/>
    </a>
    <br>
    yii2-widget-summernote
    <hr>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DTP3NZQ6G2AYU"
       title="Donate via Paypal" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-summernote-samples/samples/donate.png" alt="Donate"/>
    </a>
</h1>

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-widget-summernote/v/stable)](https://packagist.org/packages/kartik-v/yii2-widget-summernote)
[![Unstable Version](https://poser.pugx.org/kartik-v/yii2-widget-summernote/v/unstable)](https://packagist.org/packages/kartik-v/yii2-widget-summernote)
[![License](https://poser.pugx.org/kartik-v/yii2-widget-summernote/license)](https://packagist.org/packages/kartik-v/yii2-widget-summernote)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-widget-summernote/downloads)](https://packagist.org/packages/kartik-v/yii2-widget-summernote)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-widget-summernote/d/monthly)](https://packagist.org/packages/kartik-v/yii2-widget-summernote)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-widget-summernote/d/daily)](https://packagist.org/packages/kartik-v/yii2-widget-summernote)

A WYSIWYG rich text HTML output editor which uses the Summernote plugin and is styled for both Bootstrap 3.x & 4.x.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Check the [composer.json](https://github.com/kartik-v/yii2-widget-summernote/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

To install, either run

```
$ php composer.phar require kartik-v/yii2-widget-summernote "@dev"
```

or add

```
"kartik-v/yii2-widget-summernote": "@dev"
```

to the ```require``` section of your `composer.json` file.

Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-widget-summernote/blob/master/CHANGE.md) for details of release wise changes.

## Demo

You can refer detailed [documentation and demos](http://demos.krajee.com/widget-details/summernote) on usage of the extension.

## Usage

```php
use kartik\summernote\SummernoteEditor;

// Usage with ActiveForm and model
echo $form->field($model, 'content')->widget(SummernoteEditor::classname(), [
    'options' => ['accept' => 'image/*'],
]);

// With model & without ActiveForm
echo '<label class="control-label">Add Attachments</label>';
echo SummernoteEditor::widget([
    'model' => $model,
    'attribute' => 'html_content',
    'options' => ['multiple' => true]
]);
```

## License

**yii2-widget-summernote** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.