$(function () {
    if (typeof ($.Redactor) !== 'undefined') {
        $.Redactor.prototype.specialchars = function () {
            return {
                init: function () {
                    const buttonDash = this.button.add('mdash', '&mdash;');
                    this.button.setAwesome('mdash', 'fa-minus');
                    this.button.addCallback(buttonDash, this.specialchars.insertChar);

                    const buttonLaquo = this.button.add('laquo', '&laquo;');
                    this.button.setAwesome('laquo', 'fa-angle-double-left');
                    this.button.addCallback(buttonLaquo, this.specialchars.insertChar);

                    const buttonRaquo = this.button.add('raquo', '&raquo;');
                    this.button.setAwesome('raquo', 'fa-angle-double-right');
                    this.button.addCallback(buttonRaquo, this.specialchars.insertChar);

                    const buttonRur = this.button.add('8399', '&#8399;');
                    this.button.setAwesome('8399', 'fa-rub');
                    this.button.addCallback(buttonRur, this.specialchars.insertSign);

                    const buttonUsd = this.button.add('36', '&#36;');
                    this.button.setAwesome('36', 'fa-usd');
                    this.button.addCallback(buttonUsd, this.specialchars.insertSign);

                    const buttonEur = this.button.add('8364', '&#8364;');
                    this.button.setAwesome('8364', 'fa-euro');
                    this.button.addCallback(buttonEur, this.specialchars.insertSign);

                    const buttonForm = this.button.add('form', 'Форма');
                    this.button.setAwesome('form', 'fa-list');
                    this.button.addCallback(buttonForm, this.specialchars.insertForm);

                    const buttonWapp = this.button.add('wapp', 'Whatsapp');
                    this.button.setAwesome('wapp', 'fa-whatsapp ');
                    this.button.addCallback(buttonWapp, this.specialchars.insertWapp);

                    const buttonHash = this.button.add('hash', 'Хэштег');
                    this.button.setAwesome('hash', 'fa-hashtag');
                    this.button.addCallback(buttonHash, this.specialchars.insertHashtag);
                },
                insertChar: function (text) {
                    this.insert.html('&' + text + ';');
                },
                insertHashtag: function () {
                    this.insert.html('#HASHTAG#');
                },
                insertForm: function () {
                    this.insert.html('[FORM]');
                },
                insertWapp: function () {
                    this.insert.html('[WAPP]');
                },
                insertSign: function (text) {
                    this.insert.html('&#' + text + ';');
                },
            };
        };
    }
});