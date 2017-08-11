
var ComplexIndexFLAG;
$(document).ready(function() {
    if (ComplexIndexFLAG == true) {
        return;
    }
    ComplexIndexFLAG = true;

    $(document).on('click', '#Gridcomplex .grid-cell .btn[name="update"]', function(){
        var data = $(this).data();
        //var action = $(this).attr('name');
        var url = data['url'] + '/id/' + data['id'];

        var saveData = function(dialog) {
            var form = $(dialog).find('#EditComplex');
            $.ajax({
                url: url,
                method: 'POST',
                data: $(form).serialize(),
                success: function(data, textStatus, jqXHR) {
                    $(dialog).find('div.modal-body').html(data);
                    if (jqXHR.status == '202') {
                        $(dialog).modal('hide');
                        $('#Gridcomplex').jqGrid('resetSelection').trigger('reloadGrid');
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

        var dialogWin = new commonDialog('Редактирование ЖК',
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

    $(document).on('click', '#add-complex', function(){
        document.location.href = "/nedvizhimost/add";
        // var url = '/nedvizhimost/add';

        // var saveData = function(dialog) {
        //     var form = $(dialog).find('#AddComplex');
        //     $.ajax({
        //         url: url,
        //         method: 'POST',
        //         data: $(form).serialize(),
        //         success: function(data, textStatus, jqXHR) {
        //             $(dialog).find('div.modal-body').html(data);
        //             if (jqXHR.status == '202') {
        //                 $(dialog).modal('hide');
        //                 $('#Gridcomplex').jqGrid('resetSelection').trigger('reloadGrid');
        //             }
        //             return false;
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //
        //         }
        //     });
        //
        //     return false;
        // };
        //
        // var formHtml = '';
        // $.ajax({
        //     url: url,
        //     method: 'GET',
        //     async: false,
        //     success: function(data) {
        //         formHtml = data;
        //     },
        //     error: function(jqXHR, textStatus, errorThrown) {
        //         formHtml = '<p class="text-error">'+ errorThrown +'</p>';
        //     }
        // });
        //
        // var dialogWin = new commonDialog('Добавление ЖК',
        //     formHtml,
        //     [{
        //         'data-button-key': 'OK',
        //         text: 'ОК',
        //         click: saveData
        //
        //     }],
        //     {
        //         backdrop: 'static'
        //     }
        // );
        //
        // return false;
    });


    $('#Gridcomplex').bind('jqGridGridComplete', function() {
        $('#Gridcomplex .gridCheckboxOnOff').bootstrapToggle({
            on: 'Активно',
            off: 'Неактивно',
            size: 'mini',
            onstyle: 'success',
            offstyle: 'danger'

        });
    });

    $(document).on('change', '#Gridcomplex .gridCheckboxOnOff', function() {
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
            url: '/nedvizhimost/complex-toggle-remove/id/'+id+'/del/'+del,
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

    //$(document).on('click', '#Gridcomplex .toggle ', function() {
    //    alert('TTT'+$(this).find('.gridCheckboxOnOff').prop('checked'));
    //    if ($(this).find('.gridCheckboxOnOff').prop('checked') == true) {
    //        $(this).find('.gridCheckboxOnOff').prop('checked', false).change();
    //    } else {
    //        $(this).find('.gridCheckboxOnOff').prop('checked', true).change();
    //    }
    //});
});