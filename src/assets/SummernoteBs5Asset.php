<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 */

namespace kartik\editors\assets;

/**
 * Asset bundle for Summernote Bootstrap 5.x styling
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class SummernoteBs5Asset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->addDependency(SummernoteAsset::class);
        $this->setSourcePath(__DIR__ . '/lib');
        $this->setupAssets('js', ['js/kv-summernote-bs5']);
        $this->setupAssets('css', ['css/kv-summernote-bs5']);
        parent::init();
    }
}
