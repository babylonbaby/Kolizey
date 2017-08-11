/**
 * Печать формы
 * ============
 *
 * 1. На форму добавить элемент button[name='print']:
 *      $this->add([
 *          'name' => 'print',
 *          'type' => 'button',
 *          'options' => [
 *              'label' => 'Печать'
 *          ]
 *      ]);
 *
 * 2. В assetic.config.php обеспечить подгрузку js:
 *      '@kpp_printPlugin_js',
 *      '@kpp_printForm_js',
 *
 * 3. 'printCallBack' не обеспечивает контроль действительно ли напечатана форма.
 *     Callback срабатывает при закрытии окна печати.
 */

var printForm = printForm || function (formSelector) {
    $(formSelector).print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: '/css/bootstrap.min.css',
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred().done(printCallBack),
        timeout: 150,
        title: null,
        doctype: '<!doctype html>'
    });
}

var printCallBack = printCallBack || function() {
    console.log('Form printing complete');
};

$(document).ready(function() {
    $("form").on("click", "button[name='print']", function (e) {
        var _self = $(this),
            $form = _self.parents('form').clone();

        $form.find("button[name='print']").hide();

        printForm($form);
    });
});
