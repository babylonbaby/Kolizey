/**
 * Created by kota on 09.03.17.
 */
'use strict';

var dialogUiFLAG;

/**
 * Общий способ создания диалога в рамках проекта (ui-диалог)
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

        var buttonList = [];
        var buttonItem;
        /**
         * Формирует обработчик на событие click на основании переданной функции обратного вызова.
         * @param handleFun
         * @returns {Function}
         */
        var getClickHandle = function(handleFun) {
            return function() {
                if (handleFun($(this).dialog('widget')) != false) {
                    $(this).dialog("close");
                }
            }
        };

        for (var buttonKey in buttons) {
            var button = {};
            if (buttons.hasOwnProperty(buttonKey) == false) {continue;}

            buttonItem = buttons[buttonKey];
            for (var att  in buttonItem) {
                if (buttonItem.hasOwnProperty(att) == false) { continue; }

                if (att == 'click') {
                    var clickHdl = buttonItem['click'];
                    button['click'] = getClickHandle(clickHdl);
                } else {
                    button[att] = buttons[buttonKey][att];
                }
            }
            if (button['click'] == undefined) {
                button['click'] = function() { //если нет обработчика, тогда любая кнопка закрывает диалог
                    $(this).dialog("close");
                }
            }

            buttonList.push(button);
        }

        var dialogOptions = {
            buttons: buttonList,
            close: function (event, ui) {
                $(this).remove();
            },
            resizable: false,
            title: header,
            modal: true
        };

        for (var key in options) {
            if (options.hasOwnProperty(key) == false) { continue; }
            dialogOptions[key] = options[key];
        }


        var dialogClassSelector = options['dialogClassSelector'] || '';

        var dialog = $('<div class="' + dialogClassSelector + '"></div>').html(body).dialog( dialogOptions );
        if (_self != undefined) {
            _self.dialog = dialog;

            _self.closeDialogTimeout = function(timeout) {
                window.setTimeout(function () {
                    $(_self.dialog).dialog("close");
                }, timeout);
            };
        }

        $('button.ui-button.ui-dialog-titlebar-close.close').attr('value', 'x').text('x');
        $('button.ui-button.ui-dialog-titlebar-close.close').addClass('close');
};

