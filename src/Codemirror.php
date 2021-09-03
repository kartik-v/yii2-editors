<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 */

namespace kartik\editors;

use kartik\base\InputWidget;
use kartik\editors\assets\KrajeeCodemirrorAsset;
use kartik\editors\assets\CodemirrorAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Class for wrapping the Codemirror editor javascript library. Uses custom codemirror jQuery plugin built by Krajee
 * for wrapping the core javascript library.
 *
 * @see https://codemirror.net/
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class Codemirror extends InputWidget
{
    const PRESET_JS = 'js';
    const PRESET_JSON = 'json';
    const PRESET_HTML = 'html';
    const PRESET_PHP = 'php';
    const PRESET_GFM = 'gfm'; // github flavored markdown

    /**
     * @var string the default code mirror theme
     */
    const DEFAULT_THEME = 'default';

    /**
     * @var string|null|false the name of the preset to use. See the PRESET constants. This will be merged with
     * Krajee preset settings when [[useKrajeePresets]] is set to true). Set this to null or false if you do not want
     * any preset. You can also set a preset and override the settings in `pluginOptions`.
     */
    public $preset = self::PRESET_HTML;

    /**
     * @inheritdoc
     */
    public $pluginName = 'kvCodemirror';

    /**
     * @var bool whether to use Krajee presets for toolbar and other plugin settings. You can override these through
     * plugin options;
     */
    public $useKrajeePresets = true;

    /**
     * @var bool whether to enable the toolbar button for pretty formatting code
     */
    public $enablePrettyFormat = true;

    /**
     * @var array a list of addon library component files to include from the Codemirror CDN. Enter as relative
     * path to the Codemirror CDN base url.
     */
    public $libraries = [];

    /**
     * @var array default Krajee presets for the Codemirror plugin
     */
    public $krajeePresets = [
        'theme' => self::DEFAULT_THEME,
        'mode' => 'htmlmixed',
        'lint' => true,
        'lineNumbers' => true,
        'styleActiveLine' => true,
        'matchBrackets' => true,
        'smartIndent' => true,
    ];

    /**
     * @var array HTML attributes of the container to render the toolbar and editor. The following special
     * attributes are recognized:
     * - `tag`: _string_, the HTML tag used for rendering the container. Defaults to `div`.
     */
    public $container = [];

    /**
     * @var array the toolbar configuration
     */
    public $toolbar = [
        'actions' => ['buttons' => ['undo', 'redo', 'selectall']],
        'edit' => ['buttons' => ['copy', 'cut', 'paste']],
        'format' => ['buttons' => ['indent', 'outdent', 'format']],
        'comment' => ['buttons' => ['comment', 'uncomment']],
        'view' => ['buttons' => ['fullscreen'], 'options' => ['class' => 'pull-right ml-auto']],
    ];

    /**
     * @var array the HTML attributes for the toolbar
     */
    public $toolbarOptions = ['class' => 'btn-toolbar'];

    /**
     * @var string the position of the toolbar with respect to the editor (can be either `top` or `bottom`). Defaults
     * to `top`.
     */
    public $toolbarPosition = 'top';

    /**
     * @var array toolbar buttons configuration. See [[defaultButtons]] and [[initButtons()]] to understand the
     * default toolbar button configuration.
     */
    public $buttons = [];

    /**
     * @var array HTML attributes for the toolbar buttons that will be globally applied.
     */
    public $buttonOptions = ['class' => 'btn btn-default btn-light', 'data-tooltips' => 'true'];

    /**
     * @var array HTML attributes for the toolbar button groups that will be globally applied.
     */
    public $buttonGroupOptions = ['class' => 'btn-group mr-2', 'role' => 'group'];

    /**
     * @var array list of default buttons
     */
    protected $defaultButtons = [];

    /**
     * @var array list of default presets
     */
    protected $_defaultPresets = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->initWidget();
    }

    /**
     * Initializes widget
     */
    protected function initWidget()
    {
        $this->_msgCat = 'kveditor';
        $this->initI18N(__DIR__);
        $this->initPresets();
        $this->registerAssets();
        $tag = ArrayHelper::remove($this->container, 'tag', 'div');
        if (!isset($this->container['id'])) {
            $this->container['id'] = $this->options['id'] . '-container';
        }
        if (!isset($this->container['class'])) {
            $this->container['class'] = 'form-control kv-code-container';
        }
        $this->initButtons();
        $toolbar = $this->renderToolbar();
        if (empty($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }
        $content = $this->getInput('textarea');
        Html::addCssClass($this->container, 'kv-cm-container');
        $out = $this->toolbarPosition === 'bottom' ? "{$content}\n{$toolbar}" : "{$toolbar}\n{$content}";
        return Html::tag($tag, $out, $this->container);
    }

    /**
     * Initialize presets
     */
    protected function initPresets()
    {
        $this->_defaultPresets = [
            self::PRESET_JS => [
                'modes' => ['javascript'],
                'libraries' => [
                    'addon/display/placeholder.js',
                    'addon/selection/active-line.js',
                    'addon/edit/matchbrackets.js',
                    'addon/comment/continuecomment.js',
                    'addon/comment/comment.js',
                    'mode/javascript/javascript.js',
                ],
                'continueComments' => 'Enter',
                'extraKeys' => ['Ctrl-Q' => 'toggleComment'],
            ],
            self::PRESET_JSON => [
                'mode' => 'application/json',
                'libraries' => [
                    'addon/display/placeholder.js',
                    'addon/selection/active-line.js',
                    'addon/edit/matchbrackets.js',
                    'addon/comment/continuecomment.js',
                    'addon/comment/comment.js',
                    'mode/javascript/javascript.js',
                ],
                'autoCloseBrackets' => true,
                'lineWrapping' => true,
            ],
            self::PRESET_HTML => [
                'mode' => [
                    'name' => 'htmlmixed',
                    'scriptTypes' => [
                        [
                            'matches' => new JsExpression("/\\/x-handlebars-template|\\/x-mustache/i"),
                            'mode' => null,
                        ],
                        [
                            'matches' => new JsExpression("/(text|application)\\/(x-)?vb(a|script)/i"),
                            'mode' => 'vbscript',
                        ],
                    ],
                ],
                'libraries' => [
                    'addon/display/placeholder.js',
                    'addon/selection/active-line.js',
                    'addon/selection/selection-pointer.js',
                    'addon/fold/xml-fold.js',
                    'addon/edit/matchbrackets.js',
                    'addon/edit/matchtags.js',
                    'mode/xml/xml.js',
                    'mode/javascript/javascript.js',
                    'mode/css/css.js',
                    'mode/vbscript/vbscript.js',
                    'mode/htmlmixed/htmlmixed.js',
                ],
                'selectionPointer' => true,
            ],
            self::PRESET_PHP => [
                'mode' => 'application/x-httpd-php',
                'libraries' => [
                    'addon/display/placeholder.js',
                    'addon/selection/active-line.js',
                    'addon/edit/matchbrackets.js',
                    'mode/htmlmixed/htmlmixed.js',
                    'mode/xml/xml.js',
                    'mode/javascript/javascript.js',
                    'mode/css/css.js',
                    'mode/clike/clike.js',
                    'mode/php/php.js',
                ],
                'matchBrackets' => true,
                'indentUnit' => true,
                'indentWithTabs' => true,
            ],
            self::PRESET_GFM => [
                'mode' => [
                    'name' => 'gfm',
                    'tokenTypeOverrides' => [
                        'emoji' => 'emoji'
                    ]
                ],
                'libraries' => [
                    'addon/display/placeholder.js',
                    'addon/selection/active-line.js',
                    'addon/mode/overlay.js',
                    'mode/xml/xml.js',
                    'mode/markdown/markdown.js',
                    'mode/gfm/gfm.js',
                    'mode/javascript/javascript.js',
                    'mode/css/css.js',
                    'mode/clike/clike.js',
                    'mode/meta/meta.js',
                ],
            ],
        ];
        $presets = [];
        if (!empty($this->preset) && isset($this->_defaultPresets[$this->preset])) {
            $presets = $this->_defaultPresets[$this->preset];
            $this->libraries = array_merge($this->libraries, ArrayHelper::remove($presets, 'libraries', []));
        }
        if ($this->useKrajeePresets) {
            $presets = array_replace_recursive($this->krajeePresets, $presets);
        }
        $this->pluginOptions = array_replace_recursive($presets, $this->pluginOptions);
    }

    /**
     * Initialize default buttons. Set it up as an array of key value pairs, where the key is the button name and
     * the value is the HTML attributes for the button. The special attribute `label` will parse the label to be
     * rendered for the button.
     */
    protected function initButtons()
    {
        $this->defaultButtons = [
            'undo' => [
                'label' => '<i class="fas fa-undo"></i>',
                'title' => Yii::t('kveditor', 'Undo (CTRL+Z)'),
            ],
            'redo' => [
                'label' => '<i class="fas fa-redo"></i>',
                'title' => Yii::t('kveditor', 'Redo (CTRL+Y)'),
            ],
            'selectall' => [
                'label' => '<i class="fas fa-check-double"></i>',
                'title' => Yii::t('kveditor', 'Select All (CTRL+A)'),
            ],
            'copy' => [
                'label' => '<i class="fas fa-copy"></i>',
                'title' => Yii::t('kveditor', 'Copy (CTRL+C)'),
            ],
            'cut' => [
                'label' => '<i class="fas fa-cut"></i>',
                'title' => Yii::t('kveditor', 'Cut (CTRL+X)'),
            ],
            'paste' => [
                'label' => '<i class="fas fa-paste"></i>',
                'title' => Yii::t('kveditor', 'Paste (CTRL+V)'),
            ],
            'format' => [
                'label' => '<i class="fas fa-code"></i>',
                'title' => Yii::t('kveditor', 'Pretty format'),
            ],
            'indent' => [
                'label' => '<i class="fas fa-indent"></i>',
                'title' => Yii::t('kveditor', 'Indent'),
            ],
            'outdent' => [
                'label' => '<i class="fas fa-outdent"></i>',
                'title' => Yii::t('kveditor', 'Outdent'),
            ],
            'comment' => [
                'label' => '<i class="fas fa-comment"></i>',
                'title' => Yii::t('kveditor', 'Comment'),
            ],
            'uncomment' => [
                'label' => '<i class="fas fa-comment-slash"></i>',
                'title' => Yii::t('kveditor', 'Uncomment'),
            ],
            'fullscreen' => [
                'label' => '<i class="fas fa-expand-arrows-alt"></i>',
                'title' => Yii::t('kveditor', 'Toggle full screen'),
            ],
        ];
        if (!$this->enablePrettyFormat) {
            unset($this->defaultButtons['format']);
        }
        $this->buttons = array_replace_recursive($this->defaultButtons, $this->buttons);
    }

    /**
     * Renders the toolbar
     * @return string
     */
    public function renderToolbar()
    {
        if (empty($this->buttons) || empty($this->toolbar)) {
            return '';
        }
        $groups = array_keys($this->toolbar);
        $content = '';
        foreach ($groups as $group) {
            $content .= $this->renderButtonGroup($group) . "\n";
        }
        Html::addCssClass($this->toolbarOptions, 'kv-cm-toolbar');
        return Html::tag('div', $content, $this->toolbarOptions);
    }

    /**
     * Renders a button group in the toolbar
     * @param string $name
     * @return string
     */
    public function renderButtonGroup($name)
    {
        if (empty($this->buttons) || !isset($this->toolbar[$name])) {
            return '';
        }
        $config = $this->toolbar[$name];
        $buttons = ArrayHelper::getValue($config, 'buttons', []);
        $options = ArrayHelper::getValue($config, 'options', []);
        $options = ArrayHelper::merge($this->buttonGroupOptions, $options);
        if (!isset($options['aria-label'])) {
            $options['aria-label'] = $name;
        }
        Html::addCssClass($options, "btn-group-{$name}");
        $out = '';
        foreach ($buttons as $button) {
            $out .= $this->renderButton($button) . "\n";
        }
        return Html::tag('div', $out, $options);
    }

    /**
     * Renders a button in the toolbar
     * @param string $name the name of the button
     * @return string
     */
    public function renderButton($name)
    {
        if (!isset($this->buttons[$name])) {
            return '';
        }
        $options = $this->buttons[$name];
        $label = ArrayHelper::remove($options, 'label');
        $options = ArrayHelper::merge($this->buttonOptions, $options);
        $options['data-action'] = $name;
        if (!empty($this->options['disabled']) || !empty($this->options['readonly'])) {
            $options['disabled'] = true;
        }
        return Html::button($label, $options);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        $theme = ArrayHelper::getValue($this->pluginOptions, 'theme', self::DEFAULT_THEME);
        if (empty($theme) || $theme === self::DEFAULT_THEME) {
            CodemirrorAsset::register($view)->includeLibraries($this->libraries);
        } else {
            CodemirrorAsset::register($view)->includeLibraries($this->libraries)->addTheme($theme);
        }
        KrajeeCodemirrorAsset::register($view);
        $this->registerPlugin($this->pluginName);
    }
}
