var datetimePickerFLAG;
/**
 * навешивает на элемент календарь, если он еще не навешен, и показывает его,
 * @param selector
 */
var setDateTimePicker = setDateTimePicker || function (selector) {
        var defaults = {
            format: 'd.m.Y H:i'
        };

        $(selector).each(function () {
            if ($(this).attr('data-nopicker') != undefined) {
                return;
            }
            if ($(this).data('xdsoft_datetimepicker') != undefined) {
                return;
            }

            $(this).datetimepicker({
                lang: 'ru',
                timepicker: true,
                format: ($(this).attr('data-format') || defaults['format']),
                scrollMonth: false,
                dayOfWeekStart: 1
            });
            $(this).trigger('open.xdsoft');
        });
    };

$(document).ready(function() {
    if (datetimePickerFLAG == true) {
        return;
    }
    datetimePickerFLAG = true;

    $(document).on('click', 'input[data-type="datetime"]', function(){
        setDateTimePicker($(this));
        return true;
    });
});
