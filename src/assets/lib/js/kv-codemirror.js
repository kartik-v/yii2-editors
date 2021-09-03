/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 *
 * Krajee Codemirror jQuery plugin
 */
(function (factory) {
    'use strict';
    //noinspection JSUnresolvedVariable
    if (typeof define === 'function' && define.amd) { // jshint ignore:line
        // AMD. Register as an anonymous module.
        define(['jquery'], factory); // jshint ignore:line
    } else { // noinspection JSUnresolvedVariable
        if (typeof module === 'object' && module.exports) { // jshint ignore:line
            // Node/CommonJS
            // noinspection JSUnresolvedVariable
            module.exports = factory(require('jquery')); // jshint ignore:line
        } else {
            // Browser globals
            factory(window.jQuery);
        }
    }
}(function ($) {
    'use strict';
    var KvCodemirror;
    KvCodemirror = function (element, options) {
        var self = this;
        self.$element = $(element);
        self.options = options;
        self.init();
    };
    //noinspection JSUnusedGlobalSymbols
    KvCodemirror.prototype = {
        constructor: KvCodemirror,
        init: function () {
            var self = this;
            self.editor = CodeMirror.fromTextArea(self.$element[0], self.options); // jshint ignore:line
            self.disabled = self.$element.attr('disabled');
            self.initToolbar();
        },
        initToolbar: function () {
            var self = this, editor = self.editor, $container = self.$element.closest('.kv-cm-container'),
                $toolbar = $container.find('.kv-cm-toolbar'), commentSelection = function (range, isComment) {
                    editor.commentRange(isComment, range.from, range.to);
                };
            if (self.disabled) {
                return;
            }
            $toolbar.find('[data-tooltips]').tooltip({trigger:'hover'});
            $toolbar.find('button').on('click', function () {
                var $btn = $(this), range = {from: editor.getCursor(true), to: editor.getCursor(false)},
                    noSelection = range.from === range.to, action = $btn.attr('data-action');
                switch (action) {
                    case 'undo':
                        editor.undo();
                        break;
                    case 'redo':
                        editor.redo();
                        break;
                    case 'selectall':
                        editor.execCommand('selectAll');
                        break;
                    case 'fullscreen':
                        if ($container.hasClass('fullscreen')) {
                            $container.removeClass('fullscreen');
                            $btn.removeClass('active');
                        } else {
                            $container.addClass('fullscreen');
                            $btn.addClass('active');
                        }
                        break;
                    default:
                        if (noSelection && action !== 'paste') {
                            break;
                        }
                        switch (action) {
                            case 'copy':
                            case 'cut':
                            case 'paste':
                                if (action !== 'paste') {
                                    var $tmp = $(document.createElement('textarea')).appendTo('body')
                                        .css({height: '1px', width: '1px'});
                                    $tmp.val(editor.getSelection());
                                    $tmp[0].select();
                                    document.execCommand('copy');
                                    $tmp.remove();
                                } else {
                                    navigator.clipboard.readText().then(function (text) {
                                        editor.getDoc().replaceSelection(text);
                                    }).catch(function (err) {
                                        window.console.log('Unable to read clipboard contents.', err);
                                    });
                                }
                                if (action === 'cut') {
                                    editor.getDoc().replaceSelection('');
                                }
                                break;
                            case 'indent':
                                editor.execCommand('indentMore');
                                break;
                            case 'outdent':
                                editor.execCommand('indentLess');
                                break;
                            case 'comment':
                                commentSelection(range, true);
                                break;
                            case 'format':
                                editor.autoFormatRange(range.from, range.to);
                                break;
                            case 'uncomment':
                                commentSelection(range, false);
                                break;
                        }
                }
            });
        },
        save: function () {
            var self = this;
            self.editor.save();
        },
        destroy: function () {
            var self = this;
            self.editor.toTextArea(); // jshint ignore:line
            self.editor = null;
        }
    };
    $.fn.kvCodemirror = function (option) {
        var args = Array.apply(null, arguments), retvals = [];
        args.shift();
        this.each(function () {
            var self = $(this), data = self.data('kvCodemirror'), options = typeof option === 'object' && option, opt;
            if (!data) {
                opt = $.extend(true, {}, $.fn.kvCodemirror.defaults, options, self.data());
                data = new KvCodemirror(this, opt);
                self.data('kvCodemirror', data);
            }

            if (typeof option === 'string') {
                retvals.push(data[option].apply(data, args));
            }
        });
        switch (retvals.length) {
            case 0:
                return this;
            case 1:
                return retvals[0];
            default:
                return retvals;
        }
    };
    $.fn.kvCodemirror.defaults = {};
}));