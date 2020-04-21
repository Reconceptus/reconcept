$(function () {
    'use strict';
    $(document).on('click', '.js-favor', function (e) {
        let isFavor = false;
        e.stopPropagation();
        let button = $(this);
        let id = button.data('id');
        if (typeof ($.cookie('favorites')) === 'undefined') {
            $.cookie('favorites', JSON.stringify([]), {path: '/'})
        }
        var favorites = JSON.parse($.cookie('favorites'));
        let index = $.inArray(id, favorites);
        if (index === -1) {
            favorites.push(id);
            isFavor = true;
        } else {
            favorites.splice(index,1);
        }
        $.cookie('favorites', JSON.stringify(favorites), {path: '/'});
        if (isFavor) {
            button.parent().addClass('liked');
        } else {
            button.parent().removeClass('liked');
        }
        let count = favorites.length;
        $('span.fav-counter').text(count);
        console.log(favorites)
    });
});