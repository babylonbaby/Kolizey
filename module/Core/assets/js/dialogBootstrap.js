'use strict';

var dialogBootsrapFLAG;

/**
 * Общий способ создания диалога в рамках проекта (bootstrap-диалог)
 * @type {commonDialog|*|Function}
 *
 * ожидается buttons - массив содержащий описание кнопок в виде объектов:
 * [
 *   {
 *      'text': 'Моя кнопка',
 *      'click': function(){ callback function here },
 *      ... другие атрибуты переданные кнопке
 *   }
 * ]
 *
 * кнопка Cancel - зарезервинованный ключ, для генерации кнопки закрытия диалога
 */
var commonDialog = commonDialog || function(header, body, buttons, options) {
    var _self = this;

    /**
     *  Генерация уникального идентификатора
     */
    function IDGenerator() {

        this.length = 16;
        this.timestamp = +new Date;

        var _getRandomInt = function (min, max) {
            return Math.floor(Math.random() * ( max - min + 1 )) + min;
        };

        this.generate = function () {
            var ts = this.timestamp.toString();
            var parts = ts.split("").reverse();
            var id = "";

            for (var i = 0; i < this.length; ++i) {
                var index = _getRandomInt(0, parts.length - 1);
                id += parts[index];
            }

            return id;
        }
    }
    var idGenerator = new IDGenerator();
    var dialogId = 'myModal_'+ idGenerator.generate();
    var buttonList = [];
    var buttonItem;
    /**
     * Формирует обработчик на событие click на основании переданной функции обратного вызова.
     * @param handleFun
     * @returns {Function}
     */
    var getClickHandle = function(handleFun) {
        return function() {
            var dialogEl = $('#'+dialogId);
            if (handleFun(dialogEl) != false) {
                $(dialogEl).modal('hide');
            }
        }
    };

    var cancelButton = '<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>';
    var buttonFooterButtons = '';
    for (var buttonKey in buttons) {
        if (buttons.hasOwnProperty(buttonKey) == false) {continue;}

        buttonItem = buttons[buttonKey];
        var button = '<button type="button" ';
        var clickHdlrFunction = null;
        var classBtn = 'btn btn-default';
        for (var att  in buttonItem) {
            if (buttonItem.hasOwnProperty(att) == false) { continue; }
            switch (att) {
                case 'click':
                    var clickHdl = buttonItem['click'];
                    clickHdlrFunction = getClickHandle(clickHdl);
                    //button += 'onClick="'+ clickHdlrFunction + '"';
                    break;
                case 'class':
                    classBtn += ' ' + buttons[buttonKey][att];
                    break;
                default:
                    if (att=='data-button-key' && buttons[buttonKey][att] == 'Cancel') {
                        cancelButton='';
                    }
                    button += att+'="'+buttons[buttonKey][att]+'" ';
            }
        }
        button += ' class="'+classBtn+'" ';

        if (clickHdlrFunction == null) {
            button += ' data-dismiss="modal" ';
        }
        button += '>' + buttonItem['text'] +'</button>';

        buttonFooterButtons += button;
    }

    var dialogHeader =
        '<div class="modal-header">'+
            '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
            '<h4 class="modal-title">'+ header +'</h4>'+
            '</div>';
    var dialogBody = '<div class="modal-body">' +body+ '</div>';
    var dialogFooter =
        '<div class="modal-footer">'+
            buttonFooterButtons +
            cancelButton +
            //'<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>'+
            '</div>';

    var dialogTxt =
        '<div class="modal fade" id="' + dialogId + '" role="dialog">'+
            '<div class="modal-dialog">'+
            '<div class="modal-content">'+
            dialogHeader +
            dialogBody +
            dialogFooter +
            '</div>'+
            '</div>'+
            '</div>';
    var dialog = $(dialogTxt);

    //расстановка обработчиков на кнопки
    for (buttonKey in buttons) {
        if (buttons.hasOwnProperty(buttonKey) == false) {continue;}
        buttonItem = buttons[buttonKey];
        var hdl = buttonItem['click']; //обработчик на клик
        if (hdl == undefined || buttonItem['data-button-key'] == undefined) {continue;}
        $(dialog)
            .find('.modal-footer button[data-button-key="' + buttonItem['data-button-key'] + '"]')
            .click(getClickHandle(hdl));
    }

    $.extend(options, {show: true});
    $(dialog).modal(options);

    //удаляем и из ДОМа тоже
    $(document).on('hidden.bs.modal', "#"+dialogId, function () {
        $("#"+dialogId).remove();
    });

    _self.dialog = dialog;
};

