<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @package yii2-widgets
 * @subpackage yii2-widget-summernote
 * @version 1.0.9
 */

namespace kartik\summernote;

use Yii;
use kartik\base\InputWidget;

/**
 * Wrapper for the Bootstrap SummernoteEditor JQuery Plugin by Krajee. 
 *
 * @see http://plugins.krajee.com/bootstrap-summernote
 * @see https://github.com/kartik-v/bootstrap-summernote
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 * @see https://summernote.org/
 */
class SummernoteEditor extends InputWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'summernote';

    /**
     * @inheritdoc
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        return $this->initWidget();
    }

    /**
     * Initializes widget
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    protected function initWidget()
    {
        $this->_msgCat = 'summernote';
        $this->initI18N(__DIR__);
        $this->initLanguage();
        $this->registerAssets();
        return $this->getInput('textarea');
    }

    /**
     * Registers the needed assets
     * @throws \yii\base\InvalidConfigException
     */
    public function registerAssets()
    {
        $view = $this->getView();
        SummernoteEditorAsset::register($view)->addLanguage($this->language, '', 'js/lang');
        $this->registerPlugin($this->pluginName);
    }
}
