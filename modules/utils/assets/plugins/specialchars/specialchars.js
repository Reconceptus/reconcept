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
                },
                insertChar: function (text) {
                    this.insert.html('&' + text + ';');
                },
                insertSign: function (text) {
                    this.insert.html('&#' + text + ';');
                },
            };
        };
    }
});