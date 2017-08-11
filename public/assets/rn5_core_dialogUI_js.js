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


/**
 * Created by F-Technology.
 * User: Vasyankin Alexey
 * Date: 11.04.2017
 * Time: 12:45
 * e-mail: vasyankin@f-technology.ru
 */


var commonDialogAjax = commonDialogAjax || function (form, buttons, options, callbackInit) {
        var _self = this;

        _self.uniqId = (new IDGenerator()).generate();

        _self.options = {
            dialog: $.extend({
                title: '',
                width: 850,
                modal: true,
                resizable: false,
                autoOpen: false,
                dialogClassSelector: 'dialog' + _self.uniqId
            }, options),
            buttons: Array.concat([], buttons),
            block: {
                content: '<div class="overlay-block" style="text-align: center;">' +
                '<img src="/img/loaders/loader_bw.gif" /></div>',
                bgPath: '/img/',
                imgType: 'blank'
            }
        };

        _self.dataForm = [];

        _self.form = '#' + _self.options.dialog.dialogClassSelector + ' ' + form;

        options = _self.options.dialog;

        commonDialog.apply(_self, [
            options.title,
            $('<div id="' + options.dialogClassSelector + '"></div>'),
            _self.options.buttons,
            options
        ]);

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

        /**
         * Вставка содержимого в диалоговое окно
         *
         * @param content
         * @param autoOpen
         */
        _self.setContentDialog = function (content, autoOpen) {
            $('#' + _self.options.dialog.dialogClassSelector).html(content);
            if (autoOpen == null || autoOpen) {
                _self.dialog.dialog('open');
            }
        };

        /**
         * Инициализация нужных функций при открытии\перезагрузке диалогового окна
         */
        _self.initFunctionForm = function () {
            if (callbackInit != undefined) callbackInit();

            $('table.tbl-collection input[type="hidden"][name $= "[del]"]').each(function () {
                if ($(this).val() == 1) {
                    $(this).closest('tr').hide();
                }
            });
        };


        /**
         * Обновление окна после получения данных
         *
         * @param url
         */
        _self.formUpdate = function (url) {

            _self.initFunctionForm();

            $(_self.form).ajaxForm({
                target: '#' + _self.options.dialog.dialogClassSelector,
                url: url,
                data: _self.dataForm,
                success: function (result) {
                    $.msg('unblock');
                    if (typeof result === 'object') {
                        _self.dialog.dialog('close');
                    } else {
                        _self.formUpdate(url);
                    }
                },
                error: function () {
                    $.msg('unblock');
                }
            });
        };

        /**
         * Открытие диалогового окна
         *
         * @param url
         */
        _self.open = function (url, data) {
            if (data == undefined) data = [];
            _self.dataForm = data;
            $.ajax({
                url: url,
                data: data,
                async: false,
                success: function (result) {
                    _self.setContentDialog(result, true);
                    _self.formUpdate(url);
                }
            });
        };

        /**
         * Закрытие диалогового окна
         */
        _self.close = function () {
            _self.dialog.dialog('close');
            $.msg('unblock');
        };

        /**
         * Добавляется обработка события отправки формы, при котором открывается блокирующее окошко
         */
        _self.addMsg = function () {
            $(document).bind("submit", '#' + _self.options.dialog.dialogClassSelector + ' ' + _self.form, function () {
                _self.options.block.element = $('#'+ _self.options.dialog.dialogClassSelector).closest('div.ui-dialog');
                $.msg(_self.options.block);
            });
        };

        _self.addMsg();
    };
