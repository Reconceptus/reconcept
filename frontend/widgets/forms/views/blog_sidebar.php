<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 15.02.2019
 * Time: 10:58
 */
/* @var $policy string */
?>
<div class="subscribe">
    <div class="subscribe-title">Подписаться</div>
    <div class="subscribe-form">
        <div class="form-fieldset">
            <form action="#" class="form">
                <div class="input-box">
                    <input type="text" name="email" placeholder="Оставьте email">
                    <button type="submit" class="submit-bar">
                        <i class="ico">
                            <svg xmlns="http://www.w3.org/2000/svg">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xlink:href="#icon-arrow-right"></use>
                            </svg>
                        </i>
                    </button>
                </div>
                <div class="input-box">
                    <label class="checkbox">
                        <input type="checkbox" name="accept">
                        <span class="checkbox-mark"></span>
                        <span class="checkbox-text">
                <?= $policy ?>
                    </span>
                    </label>
                </div>
            </form>
            <div class="form-success">
                <strong>Ваша заявка принята!</strong>
            </div>
        </div>
    </div>
    <div class="subscribe-text">Свежие статьи у вас в ящике.</div>
</div>
<?php
$js = <<<JS
    $('.subscribe-form form').validate({
        onfocusout: false,
        ignore: ".ignore",
        rules: {
            email: {required: true, email: true},
            accept: {required: true},
        },
        messages: {
            email: {required: ""},
            accept: {required: ""}
        },
        errorClass: 'invalid',
        highlight: function (element, errorClass) {
            $(element).closest('.input-box').addClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).closest('.input-box').removeClass(errorClass)
        },
        errorPlacement: $.noop,
        submitHandler: function () {
            var form = $('.subscribe-form form');
            var button = form.find('.submit-bar');
            button.hide();
            var data = form.serialize();
            $.ajax({
                url: '/feedback/subscribe',
                type: 'POST',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                       $('.subscribe-form .form-success strong').text(res.message);
                         $('.subscribe-form').addClass('successful');
                    }
                    project.alertMessage(res.message);
                    button.show();
                    return false;
                },
            });
        }
    });
JS;
$this->registerJs($js);
?>
