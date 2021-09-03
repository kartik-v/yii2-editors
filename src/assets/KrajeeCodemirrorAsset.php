<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 */

namespace kartik\editors\assets;

/**
 * Asset bundle for loading Krajee codemirror jQuery script
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class KrajeeCodemirrorAsset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'kartik\editors\assets\CodemirrorFormatterAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib');
        $this->setupAssets('js', ['js/kv-codemirror']);
        $this->setupAssets('css', ['css/kv-codemirror']);
        parent::init();
    }
}
