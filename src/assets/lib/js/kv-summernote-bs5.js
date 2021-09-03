/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @package yii2-editors
 * @version 1.0.1
 *
 * Krajee jQuery Plugin enhancements to support Bootstrap 5.x release for Summernote Editor
 */
var kvSummernoteBs5 = function (id) {
    var $el = $('#' + id);
    $el.find('[data-toggle=dropdown]').off('click').on('click', function (e) {
        var $t = $(this);
        if (!$t.attr('data-bs-toggle')) {
            $t.tooltip('dispose').attr('title', $t.attr('data-bs-original-title')).removeAttr('data-bs-original-title');
            $t.removeAttr('data-toggle').attr({'data-bs-toggle': 'dropdown'});
            $t.dropdown().trigger('click').off('hidden.bs.dropdown').on('hidden.bs.dropdown', function () {
                $t.dropdown('dispose').removeAttr('data-bs-toggle').attr({'data-toggle': 'dropdown'});
                $t.tooltip('hide');
            });
        }
    });
    $el.find('[data-dismiss=modal]').each(function () {
        $(this).removeAttr('data-dismiss').attr('data-bs-dismiss', 'modal');
    });
    $el.find('.form-control-file').addClass('form-control').prev('label').addClass('form-label');
};