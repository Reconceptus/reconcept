<?php

use modules\config\models\Config;

/**
 * Created by PhpStorm.
 * User: venod
 * Date: 12.02.2019
 * Time: 13:34
 */
$brief1 = Yii::$app->cache->getOrSet('brief1', function () {
    return Config::getValue('brief1');
}, 60 * 60 * 24);

$brief2 = Yii::$app->cache->getOrSet('brief1', function () {
    return Config::getValue('brief2');
}, 60 * 60 * 24);
/* @var $policy string */
?>

    <div class="support-form contacts-form">
        <div class="content content--md">
            <div class="form-fieldset">
                <div class="form">
                    <h4 class="title">Задайте ваш вопрос</h4>
                    <div class="form-box--layout">
                        <div class="form-box--aside">
                            <?php if ($brief1): ?>
                                <a href="<?= $brief1 ?>" target="_blank" class="load">
                                    <i class="ico">
                                        <svg xmlns="http://www.w3.org/2000/svg">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#icon-download"/>
                                        </svg>
                                    </i>
                                    Скачать бриф на сайт
                                </a>
                            <?php endif; ?>
                            <?php if ($brief2): ?>
                                <a href="<?= $brief2 ?>" target="_blank" class="load">
                                    <i class="ico">
                                        <svg xmlns="http://www.w3.org/2000/svg">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#icon-download"/>
                                        </svg>
                                    </i>
                                    Скачать бриф на сайт
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="form-box--main">
                            <form action="#" enctype="multipart/form-data" method="post">
                                <input type="hidden" name="url" value="<?= Yii::$app->request->getAbsoluteUrl() ?>">
                                <div class="input-box">
                                    <input type="text" name="name" placeholder="Представьтесь">
                                </div>
                                <div class="input-box">
                                    <input type="text" name="email" placeholder="Ваш email?">
                                </div>
                                <div class="input-box">
                                    <input type="text" name="phone" placeholder="Ваш телефон?">
                                </div>
                                <div class="input-box">
                                    <textarea name="message" placeholder="Расскажите о вашем проекте"
                                              rows="4"></textarea>
                                </div>
                                <div class="input-box wrapped">
                                    <label class="file input-box">
                                        <input type="file" name="file">
                                        <span class="file-mark">
                                            <svg xmlns="http://www.w3.org/2000/svg">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#icon-attachment"/>
                                            </svg>
                                        </span>
                                        <span class="file-text" data-default-text="Прикрепить файл">
                                            Прикрепить файл
                                        </span>
                                        <button type="button" class="reset">&times;</button>
                                    </label>
                                    <label class="checkbox input-box">
                                        <input name="approve" type="checkbox">
                                        <span class="checkbox-mark"></span>
                                        <span class="checkbox-text"><?= $policy ?></span>
                                    </label>
                                    <button class="submit"><span>Отправить</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="form-success">
                    <p>Спасибо!</p>
                    <p>Ваша заявка принята!</p>
                </div>
            </div>
        </div>
    </div>

<?php
$js = <<<JS
$('.contacts-form form').validate({
    onfocusout: false,
    ignore: ".ignore",
    rules: {
        name: {required: true},
        email: {required: true, email: true},
        message: {required: true},
        approve: {required: true}
    },
    messages: {
        name: {required: ""},
        email: {required: ""},
        message: {required: ""},
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
        ym(5609182,'reachGoal','targetContactsMail')
        var form = $('.contacts-form form');
        var button = form.find('.submit');
        button.hide();
        formData = new FormData(form.get(0));
        $.ajax({
            contentType: false, 
            processData: false,
            url: '/feedback/support',
            type: 'POST',
            data: formData,
            success: function (res) {
                if (res.status === 'success') {
                    $('.contacts-form').addClass('successful');
                }
                 if(res.message){
                    alert(res.message);
                }
                button.show();
            },
        });
        return false;
    }
});
JS;
$this->registerJs($js);