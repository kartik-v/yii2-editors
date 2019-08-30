<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @package yii2-widgets
 * @subpackage yii2-widget-summernote
 * @version 1.0.9
 */

namespace kartik\summernote;

/**
 * Asset bundle for SummernoteEditor Widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class SummernoteEditorAsset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setupAssets('css', ['css/summernote']);
        $this->setupAssets('js', ['js/summernote']);
        parent::init();
    }
}
