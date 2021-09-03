<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 */

namespace kartik\editors;

use kartik\base\InputWidget;
use kartik\editors\assets\CodemirrorAsset;
use kartik\editors\assets\KrajeeCodemirrorAsset;
use kartik\editors\assets\KrajeeSummernoteAsset;
use kartik\editors\assets\KrajeeSummernoteStyleAsset;
use kartik\editors\assets\SummernoteAsset;
use kartik\editors\assets\SummernoteBs5Asset;
use ReflectionException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Class for wrapping the Bootstrap Summernote Editor Javascript Plugin. Includes additional enhancements by Krajee
 * for Bootstrap 3.x and 4.x support, and ability to format code, and render preset configurable toolbars.
 *
 * @see https://summernote.org/
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class Summernote extends InputWidget
{
    /**
     * @inheritdoc
     */
    public $pluginName = 'kvSummernote';

    /**
     * @var bool whether to use styles with span
     */
    public $styleWithSpan = false;

    /**
     * @var bool whether to use Krajee presets for toolbar and other plugin settings. You can override these through
     * plugin options;
     */
    public $useKrajeePresets = true;

    /**
     * @var bool whether to use Krajee CSS modifications to the Summernote Bootstrap styles.
     */
    public $useKrajeeStyle = true;

    /**
     * @var bool whether to enable full screen zoom. Applied only when [[useKrajeePresets]] is set to `true`.
     */
    public $enableFullScreen = true;

    /**
     * @var bool whether to enable display of code view. Applied only when [[useKrajeePresets]] is set to `true`.
     */
    public $enableCodeView = true;

    /**
     * @var bool whether to enable display of help button. Applied only when [[useKrajeePresets]] is set to `true`.
     */
    public $enableHelp = true;

    /**
     * @var bool whether to enable emojis hints. If set to `true` will set hint via Github emoji API.
     */
    public $enableHintEmojis = true;

    /**
     * @var array set a list of words displayed as hints when you type. If empty or not set, will not be displayed.
     */
    public $hintWords = [];

    /**
     * @var array set a list of mentions displayed when typed with `@` prefix. If empty or not set, will not be
     * displayed.
     */
    public $hintMentions = [];

    /**
     * @var bool whether to autoformat HTML code when switching to code view mode.
     */
    public $autoFormatCode = true;

    /**
     * @var array HTML attributes of the container to render the editor. The following special
     * attributes are recognized:
     * - `tag`: _string_, the HTML tag used for rendering the container. Defaults to `div`.
     */
    public $container = [];

    /**
     * @var array default Krajee presets for the summernote plugin
     */
    protected $krajeePresets = [
        'height' => 300,
        'dialogsFade' => true,
        'toolbar' => [
            ['style1', ['style']],
            ['style2', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript']],
            ['font', ['fontname', 'fontsize', 'color', 'clear']],
            ['para', ['ul', 'ol', 'paragraph', 'height']],
            ['insert', ['link', 'picture', 'video', 'table', 'hr']],
        ],
        'fontSizes' => ['8', '9', '10', '11', '12', '13', '14', '16', '18', '20', '24', '36', '48'],
        'codemirror' => [
            'theme' => Codemirror::DEFAULT_THEME,
            'lineNumbers' => true,
            'styleActiveLine' => true,
            'matchBrackets' => true,
            'smartIndent' => true,
        ],
    ];

    /**
     * @inheritdoc
     * @throws ReflectionException
     */
    public function run()
    {
        return $this->initWidget();
    }

    /**
     * Initializes widget
     * @throws ReflectionException
     */
    protected function initWidget()
    {
        $this->_msgCat = 'kveditor';
        $this->initI18N(__DIR__);
        $this->initLanguage('lang', true);
        if (!empty($this->options['placeholder']) && empty($this->pluginOptions['placeholder'])) {
            $this->pluginOptions['placeholder'] = $this->options['placeholder'];
        }
        if (!isset($this->pluginOptions['styleWithSpan'])) {
            $this->pluginOptions['styleWithSpan'] = $this->styleWithSpan;
        }
        $tag = ArrayHelper::remove($this->container, 'tag', 'div');
        if (!isset($this->container['id'])) {
            $this->container['id'] = $this->options['id'].'-container';
        }
        if (!isset($this->container['class'])) {
            $this->container['class'] = 'form-control kv-editor-container';
        }
        $this->initKrajeePresets();
        $this->initHints();
        $this->registerAssets();

        return Html::tag($tag, $this->getInput('textarea'), $this->container);
    }

    /**
     * Initializes Krajee preset toolbar
     */
    protected function initKrajeePresets()
    {
        if (!$this->useKrajeePresets) {
            return;
        }
        $toolView = [];
        if ($this->enableHelp) {
            $toolView[] = 'help';
        }
        if ($this->enableCodeView) {
            $toolView[] = 'codeview';
        }
        if ($this->enableFullScreen) {
            $toolView[] = 'fullscreen';
        }
        if (!empty($toolView)) {
            $this->krajeePresets['toolbar'][] = ['view', $toolView];
        }
        foreach ($this->krajeePresets as $key => $setting) {
            if (!isset($this->pluginOptions[$key]) && ($key !== 'codemirror' || $this->enableCodeView)) {
                $this->pluginOptions[$key] = $setting;
            }
        }
    }

    /**
     * Initialize summernote editor plugin hints
     */
    protected function initHints()
    {
        $hint = ArrayHelper::getValue($this->pluginOptions, 'hint', []);
        if (!empty($this->hintWords)) {
            $hint[] = [
                'words' => $this->hintWords,
                'match' => new JsExpression('/\b(\w{1,})$/'),
                'search' => new JsExpression(
                    'function (keyword, callback) {'.
                    '    callback($.grep(this.words, function (item) {'.
                    '        return item.indexOf(keyword) === 0;'.
                    '    }));'.
                    '}'
                ),
            ];
        }
        if (!empty($this->hintMentions)) {
            $hint[] = [
                'mentions' => $this->hintMentions,
                'match' => new JsExpression('/\B@(\w*)$/'),
                'search' => new JsExpression(
                    'function (keyword, callback) {'.
                    '    callback($.grep(this.mentions, function (item) {'.
                    '        return item.indexOf(keyword) == 0;'.
                    '    }));'.
                    '}'
                ),
                'content' => new JsExpression('function (item) { return "@" + item; }'),
            ];
        }
        if ($this->enableHintEmojis) {
            /** @noinspection RequiredAttributes */
            /** @noinspection HtmlRequiredAltAttribute */
            /** @noinspection HtmlUnknownTarget */
            $hint[] = [
                'match' => new JsExpression('/:([\-+\w]+)$/'),
                'search' => new JsExpression(
                    'function (keyword, callback) {'.
                    '    callback($.grep(kvEmojis, function (item) {'.
                    '        return item.indexOf(keyword) === 0;'.
                    '    }));'.
                    '}'
                ),
                'template' => new JsExpression(
                    'function (item) {'.
                    '    var content = kvEmojiUrls[item];'.
                    '    return \'<img src="\' + content + \'" width="20" /> :\' + item + \':\''.
                    '}'
                ),
                'content' => new JsExpression(
                    'function (item) {'.
                    '    var url = kvEmojiUrls[item];'.
                    '    if (url) {'.
                    '        return $("<img />").attr("src", url).css("width", 20)[0];'.
                    '    }'.
                    '    return "";'.
                    '}'
                ),
            ];
        }
        $this->pluginOptions['hint'] = $hint;
        $this->pluginOptions['customOptions'] = [
            'enableHintEmojis' => $this->enableHintEmojis,
            'autoFormatCode' => $this->enableCodeView && $this->autoFormatCode,
        ];
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        if ($this->enableCodeView) {
            $theme = ArrayHelper::getValue($this->pluginOptions, 'codemirror.theme', Codemirror::DEFAULT_THEME);
            if (empty($theme) || $theme === Codemirror::DEFAULT_THEME) {
                CodemirrorAsset::register($view)->includeLibraries(['mode/xml/xml.js']);
            } else {
                CodemirrorAsset::register($view)->includeLibraries(['mode/xml/xml.js'])->addTheme($theme);
            }
            if ($this->autoFormatCode) {
                KrajeeCodemirrorAsset::register($view);
            }
        }
        KrajeeSummernoteAsset::register($view);
        SummernoteAsset::register($view)->setLanguage($this->language);
        if ($this->useKrajeeStyle) {
            KrajeeSummernoteStyleAsset::register($view);
        }
        $this->registerPlugin($this->pluginName);
    }

    /**
     * @inheritDoc
     */
    public function getPluginScript($name, $element = null, $callback = null, $callbackCon = null)
    {
        $script = parent::getPluginScript($name, $element, $callback, $callbackCon);
        if ($this->isBs(5)) {
            SummernoteBs5Asset::register($this->getView());
            $script .= "\nkvSummernoteBs5('{$this->container['id']}');";
        }
        return $script;
    }
}
