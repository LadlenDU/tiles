"use strict";   //TODO: не надо ли убрать "use strict" ???
/** @type {Object} - Содержит общие вспомогательные функции. */
app.helper = {};

app.dataTableInfo = {
    "language": {
        "processing": "Подождите...",
        "search": "Поиск:",
        "lengthMenu": "Показать _MENU_ записей",
        "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
        "infoEmpty": "Записи с 0 до 0 из 0 записей",
        "infoFiltered": "(отфильтровано из _MAX_ записей)",
        "infoPostFix": "",
        "loadingRecords": "Загрузка записей...",
        "zeroRecords": "Записи отсутствуют.",
        "emptyTable": "В таблице отсутствуют данные",
        "paginate": {
            "first": "Первая",
            "previous": "Предыдущая",
            "next": "Следующая",
            "last": "Последняя"
        },
        "aria": {
            "sortAscending": ": активировать для сортировки столбца по возрастанию",
            "sortDescending": ": активировать для сортировки столбца по убыванию"
        }
    }
};

/**
 * Запись в консоль (если работает).
 *
 * @function log
 * @param {string} msg - Строка для записи.
 */
app.helper.log = function (msg) {
    if (console && console.log) {
        console.log(msg);
    }
};

/**
 * Возвращает параметр из текущего URL.
 *
 * @function getUrlParam
 * @param {string} name - Название параметра.
 * @returns {string} - Значение параметра, null если параметр не указан, пустая строка если не установлен.
 */
app.helper.getUrlParam = function (name) {
    // TODO: возможно стоит переместить функцию в window.location.href.prototype
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    } else {
        return results[1] || '';
    }
};

app.helper.checkCookieEnabled = function () {
    var cookieEnabled = navigator.cookieEnabled;
    if (!cookieEnabled) {
        document.cookie = "testcookie";
        cookieEnabled = document.cookie.indexOf("testcookie") != -1;
    }
    return cookieEnabled;
};

app.helper.implode = function (glue, pieces) {
    return ((pieces instanceof Array) ? pieces.join(glue) : pieces);
};

app.helper.extend = function (Child, Parent) {
    var F = function () {
    };
    F.prototype = Parent.prototype;
    Child.prototype = new F();
    Child.prototype.constructor = Child;
    Child.superclass = Parent.prototype;
};

//TODO: возможно, не следует менять прототипы из соображений производительности

if (!String.prototype.trim) {
    (function () {
        /**
         * Вырезает BOM и неразрывный пробел с начала и конца строки.
         *
         * @returns {string}
         */
        String.prototype.trim = function () {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
    })();
}

if (!String.prototype.sprintf) {
    (function () {
        /**
         * Заменяет значения '%s' в строке на параметры функции что идут после параметра строки.
         *
         * @param {...string} - Подставляемые аргументы.
         * @returns {string} - Модифицированная строка.
         */
        String.prototype.sprintf = function () {
            //return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
            var modStr = this.toString();
            for (var i = 0; i < arguments.length; i++) {
                modStr = modStr.replace("%s", arguments[i]);
            }
            return modStr;
        };
    })();
}
