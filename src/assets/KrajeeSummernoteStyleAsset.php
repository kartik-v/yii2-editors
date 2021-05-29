<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.0
 */

namespace kartik\editors\assets;

/**
 * Asset bundle for Krajee Bootstrap CSS Modifications to Summernote Widget.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class KrajeeSummernoteStyleAsset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'kartik\editors\assets\SummernoteAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib');
        $this->setupAssets('css', ['css/kv-summernote']);
        parent::init();
    }
}
