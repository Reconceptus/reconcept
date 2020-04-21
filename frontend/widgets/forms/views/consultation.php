<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 30.01.2019
 * Time: 11:29
 */
/* @var $policy string */
?>

    <div class="consultation">
        <div class="content">
            <div class="title">
                <span>Д</span>
                <span>е</span>
                <span>й</span>
                <span>с</span>
                <span>т</span>
                <span>в</span>
                <span>у</span>
                <span>й</span>
                <span>!</span>
            </div>
        </div>
        <div class="content content--xs">
            <div class="consultation-form">
                <div class="text">
                    Оставьте ваши контакты и мы ответим в течение 10 минут.
                </div>
                <div class="form-fieldset">
                    <form action="/" class="form">
                        <input type="hidden" name="url" value="<?= Yii::$app->request->getAbsoluteUrl() ?>">
                        <div class="input-box fieldset">
                            <input type="text" name="email" placeholder="Оставьте телефон или мейл">
                            <button class="submit"><span>Заказать консультацию</span></button>
                        </div>
                        <div class="input-box">
                            <label class="checkbox">
                                <input type="checkbox" name="approve">
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
$('.consultation-form form').validate({
    onfocusout: false,
    ignore: ".ignore",
    rules: {
        email: {required: true},
        approve: {required: true}
    },
    messages: {
        email: {required: ""},
        approve: {required: ""}
    },
    errorClass: 'invalid',
    highlight: function(element, errorClass) {
        $(element).closest('.input-box').addClass(errorClass);
    },
    unhighlight: function(element, errorClass) {
        $(element).closest('.input-box').removeClass(errorClass)
    },
    errorPlacement: $.noop,
    submitHandler:function () {
        var form = $('.consultation-form form');
        var button = form.find('.submit');
        button.hide();
        var data = form.serialize();
        $.ajax({
            url: '/feedback/consultation',
            type: 'POST',
            data: data,
            success: function (res) {
                if (res.status === 'success') {
                    $('.consultation-form').addClass('successful');
                }
                button.show();
            },
        });
        return false;
    }
})
JS;
$this->registerJs($js);