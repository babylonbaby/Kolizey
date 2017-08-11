/**
 */
$(document).ready(function(){
    /** запрещаем кэширование ajax запросов в связи с кривизной ИЕ */
    $.ajaxSetup({ cache: false });
});

/**
 * Синхронизирует изменения значения элемента с видимым значением.
 * Применяется при работе с формой в режими view
 * Т.к. в этом случае значение элемента хранится в hidden поле, то его изменение должно
 * менять видимое значение элемента, которое заключено в span в этом же блоке.
 */
function syncronizeElement(element) {
    var value = $(element).val();
    $(element).siblings('span').each(function(){
        $(this).html(value);
    });
}

var FormDecoratorFLAG;

var errorTooltip = errorTooltip || function(selector) {
        //$(selector).find('.input-error-element').each(function() {
        $(selector).each(function() {
            if ($(this).data('ui-tooltip') != undefined) {
                return;
            }

            $(this).tooltip({
                //tooltipClass: 'error-tooltip',
                items: '.input-error-element',
                content: function() {
                    var errorText = '';
                    var ulError = $(this).siblings('ul.input-error-list');

                    $('li', ulError).each(function () {
                        errorText += $(this).text().length > 0 ? $(this).text() : '';
                    });
                    return errorText;
                },
                position: {
                    my: "left top+25",
                    at: "left bottom"
                },
                track: true
            });
            $(this).tooltip('open');
        });
    };

$(document).ready(function() {
    if (FormDecoratorFLAG == true) {
        return;
    }
    FormDecoratorFLAG = true;

    $(document).on('mouseover', '.input-error-element', function(){
        errorTooltip($(this));
    });
});

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
