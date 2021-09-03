<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 */

namespace kartik\editors\assets;

/**
 * Asset bundle for loading code mirror core library assets from Codemirror CDN.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class CodemirrorAsset extends BaseAsset
{
    /**
     * @inheritdoc
     */
    public $baseUrl = '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setupAssets('css', ['codemirror']);
        $this->setupAssets('js', ['codemirror']);
        parent::init();
    }

    /**
     * Add codemirror theme
     * @param string $theme
     * @return $this
     */
    public function addTheme($theme)
    {
        return $this->setAssetFile('css', "theme/{$theme}");
    }

    /**
     * Add multiple codemirror libraries
     * @param array $files list of files
     * @return $this
     */
    public function includeLibraries($files = [])
    {
        if (!empty($files)) {
            foreach ($files as $file) {
                if (substr($file, -3) === '.js') {
                    $this->js[] = $file;
                } elseif (substr($file, -4) === '.css') {
                    $this->css[] = $file;
                }
            }
        }
        return $this;
    }
}
