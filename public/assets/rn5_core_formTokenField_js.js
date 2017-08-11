/**
 * Created by kota on 31.03.17.
 */

var setupTokenfieldElements = setupTokenfieldElements || function(selector) {
        $(selector).find('input[data-need-tokenfield]').each(function(event) {
            var tokensString = $(this).attr('data-tokens');
            if (tokensString == undefined) {
                return;
            }
            var separator = $(this).attr('data-separator') || '@@';
            var tokens = JSON.parse(tokensString);
            //console.log('tokenString = ', tokensString);
            //console.log('tokenJSON = ', tokens);
            $(this)
                .on('tokenfield:createtoken', function (e) {
                    //особенности плагина-метод вызывается несколько раз в процессе создания токена
                    if (e.attrs.value[0] != '{') {
                        //console.log('EE', e);
                        e.attrs.value = JSON.stringify({
                            value: e.attrs.value,
                            label: e.attrs.label
                        });
                    }

                })
                .on('tokenfield:removedtoken', function (e) {
                    //NB!!! информируем, что изменение данных закончено,т.к. оно может меняться не за один раз, то событие change не подходит
                    $(this).trigger('change_finished');
                })
                .tokenfield({
                    tokens: tokens,
                    delimiter: separator,
                    allowEditing: false
                });
            if ($(this).attr('readonly') != undefined) {
                $(this).tokenfield('readonly');
            }
            $(this).removeAttr('data-need-tokenfield');

            $(this).parent().each(function(){
                $(this).addClass('ext-select-wrapper');
            });

        })
    };

$(document).ready(function() {
   $(document).ajaxSuccess(function() {
       setupTokenfieldElements(this);
   });
    setupTokenfieldElements(this);
});