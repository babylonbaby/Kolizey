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