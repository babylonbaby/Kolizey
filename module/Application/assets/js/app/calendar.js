/**
 * навешивает на элемент календарь, если он еще не навешен, и показывает его,
 * @param selector
 */
var setDataPicker = setDataPicker || function (selector) {
        var defaults = {
            format: 'd.m.Y'
        };

        $(selector).each(function () {
            if ($(this).attr('data-nopicker') != undefined) {
                return;
            }
            if ($(this).data('xdsoft_datetimepicker') != undefined) {
                return;
            }
            //console.log('Create calendar');

            $(this).datetimepicker({
                lang: 'ru',
                timepicker: false,
                format: ($(this).attr('data-format') || defaults['format']),
                scrollMonth: false,
                dayOfWeekStart: 1
            });
            $(this).trigger('open.xdsoft');
        });
    };

$(document).ready(function() {


    $(document).on('click', 'input[data-type="date"]', function(){
        setDataPicker($(this));
    });
});
