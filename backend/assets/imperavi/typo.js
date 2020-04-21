(function($)
{
    $.Redactor.prototype.typo = function()
    {
        return {
            init: function ()
            {
                var that = this;
                var button = this.button.add('typo fa fa-quote-right','Кавычки');
                this.button.addCallback(button, this.typo.insert);
            },
            set: function (value)
            {
                this.inline.format('span', 'style', 'font-family:' + value + ';');
            },
            reset: function()
            {
                this.inline.removeStyleRule('font-family');
            }
        };
    };
})(jQuery);