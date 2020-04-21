<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 30.01.2019
 * Time: 11:29
 */
/* @var $policy string*/
?>

<div class="subscription">
    <div class="content">
        <h3 class="title">
            Рассылка Reconcept, подпишитесь на наш полезный <a href="/blog">блог</a>
        </h3>
    </div>
    <div class="subscription-form">
        <div class="content content--md">
            <div class="form-fieldset">
                <form action="#" class="form">
                    <div class="input-box">
                        <input type="text" name="email" placeholder="Оставьте ваш email">
                        <button class="submit-btn">
                            <i class="ico">
                                <svg xmlns="http://www.w3.org/2000/svg">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#icon-arrow-right-long"/>
                                </svg>
                            </i>
                        </button>
                    </div>
                    <div class="input-box centered">
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
                    Ваша заявка принята!
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<JS
    $('.subscription-form form').validate({
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
            var form = $('.subscription-form form');
            var button = form.find('.submit-btn');
            button.hide();
            var data = form.serialize();
            $.ajax({
                url: '/feedback/subscribe',
                type: 'POST',
                data: data,
                success: function (res) {
                    if (res.status === 'success') {
                       $('.subscription-form .form-success').text(res.message);
                        $('.subscription-form').addClass('successful');
                    }
                    button.show();
                    return false;
                },
            });
        }
    });
JS;
$this->registerJs($js);
?>
