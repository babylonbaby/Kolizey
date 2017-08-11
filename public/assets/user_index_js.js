
var UserIndexFLAG;

$(document).ready(function() {
    if (UserIndexFLAG == true) {
        return;
    }
    UserIndexFLAG = true;

    $(document).on('click', '#Gridusers .grid-cell .btn[name="update"]', function(){
        var data = $(this).data();
        //var action = $(this).attr('name');
        var url = data['url'] + '/id/' + data['id'];

        var saveData = function(dialog) {
            var form = $(dialog).find('#EditUser');
            $.ajax({
                url: url,
                method: 'POST',
                data: $(form).serialize(),
                success: function(data, textStatus, jqXHR) {
                    $(dialog).find('div.modal-body').html(data);
                    if (jqXHR.status == '202') {
                        $(dialog).modal('hide');
                        $('#Gridusers').jqGrid('resetSelection').trigger('reloadGrid');
                    }
                    return false;
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });

            return false;
        };

        var formHtml = '';
        $.ajax({
            url: url,
            method: 'GET',
            async: false,
            success: function(data) {
                formHtml = data;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                formHtml = '<p class="text-error">'+ errorThrown +'</p>';
            }
        });

        var dialogWin = new commonDialog('Редактирование пользователя',
            formHtml,
            [{
                'data-button-key': 'OK',
                text: 'ОК',
                click: saveData

            }],
            {
                backdrop: 'static'
            }
        );

        return false;
    });

    $(document).on('click', '#add-user', function(){
        var url = '/user/add';

        var saveData = function(dialog) {
            var form = $(dialog).find('#AddUser');
            $.ajax({
                url: url,
                method: 'POST',
                data: $(form).serialize(),
                success: function(data, textStatus, jqXHR) {
                    $(dialog).find('div.modal-body').html(data);
                    if (jqXHR.status == '202') {
                        $(dialog).modal('hide');
                        $('#Griduser').jqGrid('resetSelection').trigger('reloadGrid');
                    }
                    return false;
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });

            return false;
        };

        var formHtml = '';
        $.ajax({
            url: url,
            method: 'GET',
            async: false,
            success: function(data) {
                formHtml = data;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                formHtml = '<p class="text-error">'+ errorThrown +'</p>';
            }
        });

        var dialogWin = new commonDialog('Добавление пользователя',
            formHtml,
            [{
                'data-button-key': 'OK',
                text: 'ОК',
                click: saveData

            }],
            {
                backdrop: 'static'
            }
        );

        return false;
    });


    $('#Gridusers').bind('jqGridGridComplete', function() {
        $('#Gridusers .gridCheckboxOnOff').bootstrapToggle({
            on: 'Активно',
            off: 'Неактивно',
            size: 'mini',
            onstyle: 'success',
            offstyle: 'danger'

        });
    });

    $(document).on('change', '#Gridusers .gridCheckboxOnOff', function() {
        var errHdl = function (msg) {
            alert('Ошибка переключателя ' + msg);
        };
        var id = $(this).attr('data-id');
        var value = $(this).prop('checked');

        //FIXME сюда АСИНХРОННЫЙ аякс, полученный статус установить в элемент
        var del = 0;
        var gridCheckboxOnOff = $(this).find('.gridCheckboxOnOff');

        if (value == false) {
            del = 1; //новое будущее значение
        }
        $.ajax({
            url: '/user/toggle-remove/id/'+id+'/del/'+del,
            async: true,
            cache: false,
            method: 'GET',
            success: function(data) {
                if (data['status'] != 0) {
                    errHdl(data['msg']);
                }
                del = data['data'];
                if (del == 0) {
                    $(gridCheckboxOnOff).prop('checked', true).change();
                } else {
                    $(gridCheckboxOnOff).prop('checked', false).change();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                errHdl(errorThrown);
            }
        });


    });
});