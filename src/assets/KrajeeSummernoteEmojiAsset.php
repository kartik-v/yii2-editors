<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @package yii2-editors
 * @version 1.0.0
 */

namespace kartik\editors\assets;

/**
 * Asset bundle for loading Krajee github emoji parsing script.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class KrajeeSummernoteEmojiAsset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/lib');
        $this->setupAssets('js', ['js/kv-summernote-emoji']);
        parent::init();
    }
}
