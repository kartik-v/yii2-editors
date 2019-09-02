/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @package yii2-editors
 * @version 1.0.0
 *
 * Krajee Summernote emoji processing script enhancements
 */
var kvInitEmojis;
(function ($) {
    "use strict";
    kvInitEmojis = function() {
        $.ajax({
            url: 'https://api.github.com/emojis',
            async: false
        }).then(function(data) {
            window.kvEmojis = Object.keys(data);
            window.kvEmojiUrls = data;
        });
    };
})(window.jQuery);