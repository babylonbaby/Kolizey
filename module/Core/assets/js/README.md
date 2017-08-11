Помощники в обслуживании элемента "внешний селектор" и окна диалога
===================================================================

\Rn5Core\Form\Element\ExternalSelect - элемент - который позволяет выбирать значения из списка с помощью внешнего селектора.
 Это аналог select, но применяется для случаев, когда список велик, или не укладывается в концепцию key=>value, а подразумевает
 выдачу дополнительной информации пользователю кроме value.

При описании такого элемента необходимо задавать опцию 'clickHandler'
в которой указывать имя javascript обработчика, который обеспечит выбор необходимой пары id,value и поместит
их в ExternalSelect элемент.

Далее речь о clickHandler-ах.
Все clickHandler-ы сосредоточены в глобальном объекте ExternalSelectList
Каждый обработчик должен обеспечивать публичный методы
 - select(),
 - setValue(id, value)
ExternalSelectList он содержит след. стандартные обработчики:
1) ExternalSelect - базовый обработчик, реализующий метод setValue
2) ExternalSelectDialog - наследник от ExternalSelect, обеспечивает вызов диалога
посредством функции commonDialog, в которую передаются необходимые значения для формирования диалога и
включая кнопки с колбэками OK и Cancel.
В колбэк OK включен обработчик, который после выбора в диалоге значения подставит необходимые данные в
ExternalSelect - элемент

commonDialog - функция, которая зависит от используемого типа диалога.
В настоящий момент есть реализация для ui.dialog, которая находится в dialogUI.js
В случае необходимости необходимо сделать реализацию для bootstrap и т.п...

Для того чтобы обработчик на кнопке OK нормально отрабатывал необходимо, чтобы выбор осуществлялся
через radio-кнопки.
Каждая радиокнопка кроме value, содержащего идентификатор, должна иметь атрибут data-value, который содержит
user-friendly значение для ExternalSelect-элемента. Т.о. значение из атрибута value будет подставлено в id внешнего селектора,
а data-value в value внешнего селектора.

Использование jqgrid в диалоге выбора значения для внешнего селектора
---------------------------------------------------------------------
Используется jqgrid, где необходимы поля key, value;
Поле key должно представлять из себя радиокнопку вида изложенного выше.
Поле value - текстовое поле
для автоматизации сделан стандартный форматер rn5ExternalSelectFormatter, который нужно назначить полю с именем 'key'

Пример полей из базового филдсета простейшей формы, описывающей грид
    $this->add([
        'name' => 'key',
        'type' => 'text',
        'options' => [
            'label' => ' ',
            'jqGrid' => [
                'search' => false,
                'width' => 30,
                'fixed' => true,
                'formatter' => new Expr('rn5ExternalSelectFormatter'),
            ]
        ]
    ]);

    $this->add([
        'name' => 'value',
        'type' => 'text',
        'options' => [
            'label' => 'Наименование ГП/ПП',
            'jqGrid' => [
                'searchoptions' => [
                    'sopt' => ["cn", 'eq','ne','bw', "bn", "ew", "en", "nc"],
                ],
                'classes' => 'grid-cell',
                'cellattr' => new Expr('rn5CommonAttrSetting'),
                'search' => false,
            ]
        ]
    ]);

форматер rn5ExternalSelectFormatter превратит значение key в радиокнопку вида
<input type='radio' name='key' value='" + cellValue + "' data-value='"+ value +"\'>"
А колбэк, автоматически повешенный на кнопку OK закроет диалог и подставит выбранную пару id, value в ExternalSelect-элемент

Порядок действий при создании своего внешнего селектора с окном диалога:
-------------------------------------------------------
1) создаем наследник от объекта ExternalSelectDialog, например
ExternalSelectList.SelectProgram = ExternalSelectList['SelectProgram'] || function () {
    var me = this;
    ExternalSelectList.ExternalSelectDialog.apply(me, arguments); //наследуемся от ExternalSelectDialog

    //формируем необходимые даные для окна диалога
    var url = '/sp-report/report/finance-ws-results/program-list-by-period';
    $.ajax({
        async: false,
        url: url,
        success: function(data) {
            me.body = data //данные, которые будут помещены в содержимое диалога
        }
    });

    //при необходимости переопределяем кнопки через me.buttons и опции, которые будут переданы
    //в метод формирования диалога commonDialog
};
В результате данный обработчик  SelectProgram зарегистрирован в списке обработчиков

2) используем данный обработчик в элементе формы
$this->add([
    'name' => 'ExternalSelect',
    'type' => \Rn5Core\Form\Element\ExternalSelect::class,
    'attributes' => [],
    'options' => [
        'label' => 'ExternalSelect 1',
        //'clickHandler' => 'ExternalSelect',
        //'clickHandler' => 'ExternalSelectDialog',
        'clickHandler' => 'SelectProgram',
    ]
]);

3) обеспечиваем подгрузку в accets на странице.
 а) dialogUI.js - для реализации ui.dialog в '@rn5_core_dialogUI_js' или другой реализатор диалога
 б) externalSelect.js  в '@rn5_core_externalSelect_js'
 в) возможно, rn5/grid/assets/js/common.js  '@rn5_grid_common_js' в ассетсах для формирования у грида правильного radio-элемента

