/**
 * Created by kota on 04.04.17.
 */

var addRowToCollection = addRowToCollection ||  function(){
        var collectionWrapper = $(this).closest('.collection');
        var templateEl = $(collectionWrapper).children('.template');
        var dataTemplate = $(templateEl).attr('data-template');
        if (dataTemplate == undefined) { return false; }

        var tbodyEl = $(collectionWrapper).children('table.tbl-collection').first();
        tbodyEl  = $(tbodyEl).children('tbody');

        var placeholder = $(templateEl).attr('data-placeholder');
        var template = dataTemplate;

        var fullRowCount = $(tbodyEl).children('tr').length;
        template = template.replace(new RegExp(placeholder, 'g'), fullRowCount);

        $(tbodyEl).append($(template));
        return false;
    };

/**
 * Удаляет строку из коллекции.
 * предполагается, что филдсет коллекции имеет элемет с именем del, при удалении проставляем ему value=1
 *
 * @param trEl - селектор строки, которую надлежит удалить
 * @type {removeRowFromCollection|*|Function}
 */
var removeRowFromCollection = removeRowFromCollection || function(trEl) {
        $(trEl).children('td').children('input[type="hidden"][name $= "[del]"]').first().val('1');

        var len = $(trEl).children('td').children('input[type="hidden"][name $= "[del]"]').first().length;
        if (len == 0) {
            alert('В fieldset входящем в состав коллекции не определен элемент с имененм "del" - удаление невозможно');
            return false;
        }

        $(trEl).hide();
        return false;
    };

var addRowToCollectionFLAG;

$(document).ready(function() {
    if (addRowToCollectionFLAG == true) {
        return;
    }
    addRowToCollectionFLAG = true;

    $(document).on('click', 'div.collection-buttons .addButton', addRowToCollection);

    $(document).on('click', '.tbl-collection .removeButton', function () {
        var trEl = $(this).closest('tr');
        removeRowFromCollection(trEl);
        return false;
    });
});

