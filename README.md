<h1 align="center">
    <a href="http://demos.krajee.com" title="Krajee Demos" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/krajee-logo-b.png" alt="Krajee Logo"/>
    </a>
    <br>
    yii2-editors
    <hr>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DTP3NZQ6G2AYU"
       title="Donate via Paypal" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/donate.png" alt="Donate"/>
    </a>
</h1>

<div align="center">

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-editors/v/stable)](https://packagist.org/packages/kartik-v/yii2-editors)
[![Unstable Version](https://poser.pugx.org/kartik-v/yii2-editors/v/unstable)](https://packagist.org/packages/kartik-v/yii2-editors)
[![License](https://poser.pugx.org/kartik-v/yii2-editors/license)](https://packagist.org/packages/kartik-v/yii2-editors)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-editors/downloads)](https://packagist.org/packages/kartik-v/yii2-editors)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-editors/d/monthly)](https://packagist.org/packages/kartik-v/yii2-editors)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-editors/d/daily)](https://packagist.org/packages/kartik-v/yii2-editors)

</div>

Editor widgets for Yii2 framework - Summernote and Codemirror.

Summernote is a WYSIWYG rich text HTML input widget which uses the [Summernote](https://summernote.org/) 
WYSWIYG plugin and is styled for both Bootstrap 3.x & 4.x. Includes additional enhancements by Krajee for Bootstrap 3.x and 4.x support, and 
ability to format code, and render preset configurable toolbars.

Codemirror is a plain text code editor that allows syntax formatting of various code.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Check the [composer.json](https://github.com/kartik-v/yii2-editors/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

To install, either run

```
$ php composer.phar require kartik-v/yii2-editors "@dev"
```

or add

```
"kartik-v/yii2-editors": "@dev"
```

to the ```require``` section of your `composer.json` file.

Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-editors/blob/master/CHANGE.md) for details of release wise changes.

## Demo

You can see detailed [documentation and examples](http://demos.krajee.com/editors) on usage of the extension.

## Prerequisites

The Codemirror widget requires Font Awesome Icon assets rendered on the page for rendering the toolbar. You can render the 
font awesome icon assets on your page using one of the options below:

- **Option 1**: Font CSS version of Font Awesome:

```html
<!-- on your view layout file HEAD section -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
```

- **Option 2**: SVG / JS version of Font Awesome (e.g. from kartik-v/yii2-icons library):

```php
// on your view layout file
use kartik\icons\FontAwesomeAsset;
FontAwesomeAsset::register($this);
```


## Usage

### Summernote WYSIWYG Editor

![Summernote Editor Screenshot](https://user-images.githubusercontent.com/3592619/64076550-e568bd80-cce3-11e9-9c62-fe4cdf20be90.png)

```php
use kartik\editors\Summernote;

// Usage with ActiveForm and model and default settings
echo $form->field($model, 'content')->widget(Summernote::class, [
    'options' => ['placeholder' => 'Edit your blog content here...']
]);

// With model & without ActiveForm and default settings
echo Summernote::widget([
    'model' => $model,
    'attribute' => 'html_content',
]);

// Without model and setting advanced custom widget configuration options
echo Summernote::widget([
    'name' => 'blog_post',
    'value' => '',
    'useKrajeeStyle' => true,
    'useKrajeePresets' => true,
    'enableFullScreen' => true,
    'enableCodeView' => false,
    'enableHelp' => false,
    'enableHintEmojis' => true,
    'hintMentions' => ['jayden', 'sam', 'alvin', 'david']
]);
```

### Codemirror Code Editor

![Codemirror Editor Screenshot](https://user-images.githubusercontent.com/3592619/64076610-9c653900-cce4-11e9-8281-8cb7cf94f1a5.png)

```php
use kartik\editors\Codemirror;

// Usage with ActiveForm and model and default settings
echo $form->field($model, 'program_code')->widget(Codemirror::class, [
    'preset' => Codemirror::PRESET_PHP,
    'options' => ['placeholder' => 'Edit your code here...']
]);

// With model & without ActiveForm and default settings
echo Codemirror::widget([
    'model' => $model,
    'attribute' => 'json_code',
    'preset' => Codemirror::PRESET_JSON,
]);

// Without model and setting advanced custom widget configuration options
echo Codemirror::widget([
    'name' => 'js_code',
    'value' => '',
    'preset' => Codemirror::PRESET_JS,
    'useKrajeePresets' => true,
    'libraries' => [
        'addon/display/placeholder.js',
        'addon/fold/xml-fold.js',
        'addon/edit/matchbrackets.js',
        'addon/edit/matchtags.js',
        'addon/selection/active-line.js',
        'addon/selection/selection-pointer.js',
    ],
    'pluginOptions' => [
        'modes' => ['xml', 'javascript', 'css'],
    ]
]);
```

## License

**yii2-editors** is released under the BSD-3-Clause License. See the bundled `LICENSE.md` for details.