
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
