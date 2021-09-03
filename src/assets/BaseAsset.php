<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 */

namespace kartik\editors\assets;

use kartik\base\AssetBundle;

/**
 * Base editor asset
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class BaseAsset extends AssetBundle
{
    /**
     * Sets a JS or CSS asset file
     * @return $this
     */
    protected function setAssetFile($ext, $file)
    {
        $this->{$ext}[] = YII_DEBUG ? "{$file}.{$ext}" : "{$file}.min.{$ext}";
        return $this;
    }
}
