/**
 * Created by kota on 17.02.17.
 */

/**
 * функция берет поле attr из записей, поступающих в грид, и формирует атрибуты для ячеек этой записи
 * Пример данных поступающих в грид
 * [
 *      'id' =>"01", 'number' => '1', 'name' => 'Всего по программе(прог 1)', 'type' => '', 'period' => '',
 *      'responsible' => 'Иванов', 'finsource' => 'all',
 *      'year2016' => '12000', 'year2017' => '79000', 'year2018' => '100000', 'year2019' => '', 'year2020' => '',
 *      'attr' => [
 *          'number' => ['rowspan' => '5'],
 *          'name' => ['rowspan' => '5', 'colspan'=>'4'],
 *          'type' => ['style'=>'display:none;' ],
 *          'period' => ['style'=>'display:none;'],
 *          'responsible' => ['style'=>'display:none;'],
 *      ],
 * ],
 * для полей number, name, type, period, responsible будут добавлены заданные атрибуты на ячейку грида
 *
 * @param rowId
 * @param val
 * @param rawObject
 * @param cm
 * @returns {*}
 */
var CommonAttrSetting = CommonAttrSetting || function (rowId, val, rawObject, cm) {
    if (rawObject['attr'] == undefined) {
        return '';
    }
    var attr = rawObject.attr[cm.name];
    var result;
    if (attr == undefined) {
        return '';
    }

    for (var key in attr) {
        if (attr.hasOwnProperty(key) == false) { continue; }

        var value = '';
        if (key == 'class') {
            if (cm['classes'] != undefined) {
                value = cm['classes'] + ' ';
            }
        }
        value += attr[key];
        result += ' '+key+'="' + value +'"';

    }
    //console.log('CM', cm);
    return result;
};

/**
 * генератор радиокнопки для гридов, которые используются для внешнего селектора.
 * Для этого в форме грида должне быть элемент с именем value содержащий user-friendly значение, и
 * элемент с именем key, на который и вешается этот форматер
 *
 * @type {ExternalSelectFormatter|*|Function}
 */
var ExternalSelectFormatter = ExternalSelectFormatter || function(cellValue, option, rowObject) {
        if (option.colModel['formatoptions'] != undefined) {
            if (option.colModel['formatoptions']['criteria'] != undefined) {
                var res = option.colModel['formatoptions']['criteria'](cellValue, rowObject);
                if (res == false) {
                    return '';
                }
            }
        }
    var value = rowObject["value"] || "";
    return  "<input type='radio' name='key' value='" + cellValue + "' data-value='"+ value +"\'>";
};

/**
 * генератор чекбокса для гридов, которые используются для внешнего мульти-селектора.
 * Для этого в форме грида должне быть элемент с именем value содержащий user-friendly значение, и
 * элемент с именем key, на который и вешается этот форматер
 *
 * @type {ExternalSelectMultiFormatter|*|Function}
 */
var ExternalSelectMultiFormatter = ExternalSelectMultiFormatter || function(cellValue, option, rowObject) {
    if (option.colModel['formatoptions'] != undefined) {
        if (option.colModel['formatoptions']['criteria'] != undefined) {
            var res = option.colModel['formatoptions']['criteria'](cellValue, rowObject);
            if (res == false) {
                return '';
            }
        }
    }
    var value = rowObject["value"] || "";
    return  "<input type='checkbox' name='key' value='" + cellValue + "' data-value='"+ value +"\'>";
};


var GridColumnSelector = GridColumnSelector || function(gridObj, options) {
        var me = this;
        var colModel = gridObj.jqGrid('getGridParam', 'colModel');
        var cellSummaryWidth = 0;
        var fixedWidth = options['fixedWidth'];
        var gridWidth = gridObj.jqGrid('getGridParam', 'width');

        me.header = 'Управление столбцами';
        me.body = $('<div class="gridColumnSelectorWrapper"></div>');
        me.buttons = [
            {
                'data-button-key': 'OK',
                text: 'Выбрать',
                click: function(dialogElement) {
                    $(dialogElement).find('.grid-column-item input[type="checkbox"]').each(function(){
                        var checkboxName = $(this).attr('name');
                        var show =  $(this).prop('checked');
                        $.each(colModel, function(i){
                            if (this.hidedlg) { return; }
                            if (this['index'] != checkboxName) { return; }
                            if (this['hidden'] == undefined || this['hidden'] ==  !show) {
                                return; //не трогаем колонки, у которых видимость не поменялась.
                            }
                            //console.log('** hidden = ', this['hidden'], ' colname=', checkboxName, ' will set show = ', show);
                            if (show) {
                                //console.log('show =', checkboxName);
                                gridObj.jqGrid('showCol', checkboxName);
                            } else {
                                //console.log('hide = ', checkboxName);
                                gridObj.jqGrid('hideCol', checkboxName);
                            }
                        });

                    });
                    if (fixedWidth) { //подгоняем принудительно ширину и сжатие колонок под ширину.
                        cellSummaryWidth = 0;
                        $.each(colModel, function(i){
                            if (this.hidedlg) { return; }
                            if (this['width'] != undefined && this['hidden'] == false) {
                                cellSummaryWidth += this.width;
                            }
                        });
                        //console.log('cellSummaryWidth=', cellSummaryWidth, ' gridWidth=', gridWidth);
                        if (cellSummaryWidth < gridWidth) {
                            gridObj.jqGrid('setGridWidth', gridWidth, true); //растягиваем до ширины грида колонки
                        } else {
                            gridObj.jqGrid('setGridWidth', gridWidth, false); //колонки шире грида, будет скрол
                        }
                    }
                    return true;
                }
            },
            {
                'data-button-key': 'Cancel',
                text: 'Закрыть'
            }

        ];
        me.options = {
            width: 'auto'
        };

        var elementDefinition = '';
        $.each(colModel, function(i){
            if (this.hidedlg) { return; }
            if (this['width'] != undefined && this['hidden'] == false) {
                cellSummaryWidth += this.width;
            }
            if (this['classes'] != undefined) {
                var classArr = this['classes'].split(" ");
                for(j=0; j<classArr.length; ++j) {
                    if (classArr[j] == 'no-toggle-hidden') { //не всеми колонками можно управлять
                        return;
                    }
                }
            }

            elementDefinition = '<div class="grid-column-item grid-column-item-' + i +'">';
            elementDefinition += '<input type="checkbox" id="grid_column_'+ i + '" name="' + this['index'] +'" value="1"' +
                (this.hidden?"":"checked='checked'") +
            '>';
            elementDefinition += '<label for="grid_column_' + i + '">' + this['label'] + '</label></div>';
            me.body.append($(elementDefinition));
        });

        commonDialog(me.header, me.body, me.buttons, me.options);
    };

/**
 * Исключение запятых для полей с форматтером currency
 * @type {Function}
 */
var GridBeforeSaveCell = GridBeforeSaveCell || function (rowid, cellname, value, iRow, iCol){
    var optionsCol = $(this).jqGrid('getGridParam', 'colModel');
    optionsCol = optionsCol[iCol];
    if (optionsCol.formatter == 'currency') return value.replace(',', '.');
    return value;
};