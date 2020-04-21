<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 24.01.2019
 * Time: 15:01
 */

use common\models\User;
use modules\blog\models\Comment;
use modules\blog\models\Post;
use modules\config\models\Config;

/* @var $comments */
/* @var $countComments */
/* @var Comment $rootComment */
/* @var Post $model */
/* @var $level_start */


$commentsTree = $this->render('comments', ['comments' => $comments, 'level_start' => $level_start]);
if (!$commentsTree) {
    $commentsTree = '<ul class="main-menu"></ul>';
}
$user = User::getUser()
?>

<div class="comment-box">
    <div class="content content--lg">
        <div class="comment-box--main">
            <div class="comment-content">
                <div class="comment-box--head" <?= $countComments ? '' : 'style="display:none"' ?>>
                    <h3 class="comment-box--title">Комментарии (<?= $countComments ?>)</h3>
                    <a class="submit-sm" href="#top"><span>Оставьте ваш комментарий</span></a>
                </div>
                <?= $commentsTree ?>
            </div>
        </div>
    </div>
</div>

<div class="comment-box">
    <a name="top"></a>
    <div class="content content--lg">
        <div class="comment-box--main">
            <div class="comment-content">
                <div class="comment-form total-comment">
                    <h4 class="comment-box--title">ОСТАВЬТЕ КОММЕНТАРИЙ <?= $countComments ? '' : 'ПЕРВЫМ!' ?></h4>
                    <form action="#">
                        <input type="hidden" name="comment_id" value="<?= $model->rootComment->id ?>">
                        <div class="input-box">
                            <div class="col-50 stretched">
                                <label class="label input-box">
                                        <textarea name="comment"
                                                  placeholder="Оставьте ваш комментарий"></textarea>
                                </label>
                            </div>
                            <div class="col-50">
                                <label class="label input-box">
                                    <input type="text"
                                           value="<?= $user ? $user->fio : '' ?>"
                                           name="name" placeholder="Имя">
                                </label>
                                <label class="label input-box">
                                    <input type="text"
                                           value="<?= $user ? $user->email : '' ?>"
                                           name="mail"
                                           placeholder="Ваш емейл (не будет опубликован)">
                                </label>
                            </div>
                        </div>
                        <div class="input-box">
                            <label class="checkbox">
                                <input type="checkbox" name="approve">
                                <span class="checkbox-mark"></span>
                                <span class="checkbox-text">
                                        <?= Config::getValue('license_text') ?>
                                    </span>
                            </label>
                            <button class="submit-sm"><span>Опубликовать</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        function addForm(id) {
            var formCode = '<div class="comment-form" data-comment-form="' + id + '">' +
                '<form action="#">\n' +
                '    <div class="input-box"><input type="hidden" class="hidden-comment-id" name="comment_id">\n' +
                '        <div class="col-50 stretched">\n' +
                '            <label class="label input-box">\n' +
                '                <textarea name="comment" placeholder="Оставьте ваш комментарий"></textarea>\n' +
                '            </label>\n' +
                '        </div>\n' +
                '        <div class="col-50">\n' +
                '            <label class="label input-box">\n' +
                '                <input type="text" value="<?=$user ? $user->fio : ''?>" name="name" placeholder="Имя">\n' +
                '            </label>\n' +
                '            <label class="label input-box">\n' +
                '                <input type="text" value="<?=$user ? $user->email : ''?>" name="mail" placeholder="Ваш емейл (не будет опубликован)">\n' +
                '            </label>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '    <div class="input-box">\n' +
                '        <label class="checkbox">\n' +
                '            <input type="checkbox" name="approve">\n' +
                '            <span class="checkbox-mark"></span>\n' +
                '            <span class="checkbox-text">\n' +
                '                Даю согласие  на обработку персональных данных и соглашаюсь с политикой конфиденциальности.\n' +
                '            </span>\n' +
                '        </label>\n' +
                '        <button class="submit-sm"><span>Опубликовать</span></button>\n' +
                '    </div>\n' +
                '</form>' +
                '</div>';

            return formCode;
        }

        $('.comment-box').on('click', '.button', function (e) {
            e.preventDefault();

            var $thisComment = $(this).closest('.comment'),
                $thisCommentID = $thisComment.attr('data-id');

            $thisComment.addClass('opened');
            $thisComment.after(addForm($thisCommentID));

            $('[data-comment-form=' + $thisCommentID + '] form').validate({
                onfocusout: false,
                ignore: ".ignore",
                rules: {
                    comment: {required: true},
                    name: {required: true},
                    mail: {required: true, email: true},
                    approve: {required: true}
                },
                messages: {
                    comment: {required: ""},
                    name: {required: ""},
                    mail: {required: ""},
                    approve: {required: ""}
                },
                errorClass: 'invalid',
                highlight: function (element, errorClass) {
                    $(element).closest('.input-box').addClass(errorClass);
                },
                unhighlight: function (element, errorClass) {
                    $(element).closest('.input-box').removeClass(errorClass)
                },
                errorPlacement: $.noop,
                submitHandler: function (f) {
                    let form = $(f);
                    form.hide();
                    let id = form.closest('.comment-form').data('comment-form');
                    let commentField = form.find('.hidden-comment-id');
                    commentField.val(id);
                    var data = form.serialize();
                    $.ajax({
                        url: '/blog/add-new-js-ajax-comment',
                        type: 'POST',
                        data: data,
                        success: function (res) {
                            if (res.status === 'success') {
                                if (res.html) {
                                    let parent = $('.comment[data-id="' + id + '"]');
                                    let ul = parent.siblings('ul.sub-menu');
                                    if (ul.length) {
                                        ul.append('<li>' + res.html + '</li>');
                                    } else {
                                        parent.after('<ul class="sub-menu"><li>' + res.html + '</li></ul>');
                                        parent.parent().addClass('parent')
                                    }
                                }
                                $('.comment-form').remove();
                            }
                            return false;
                        },
                    });
                }
            });

        });

        $('.total-comment form').validate({
            onfocusout: false,
            ignore: ".ignore",
            rules: {
                comment: {required: true},
                name: {required: true},
                mail: {required: true, email: true},
                approve: {required: true}
            },
            messages: {
                comment: {required: ""},
                name: {required: ""},
                mail: {required: ""},
                approve: {required: ""}
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
                var form = $('.total-comment form');
                var button = form.find('.submit-sm');
                button.hide();
                var data = form.serialize();
                $.ajax({
                    url: '/blog/add-new-js-ajax-comment',
                    type: 'POST',
                    data: data,
                    success: function (res) {
                        if (res.status === 'success') {
                            if (res.html) {
                                $('.comment-box--head').show();
                                $('ul.main-menu').append('<li>' + res.html + '</li>')
                            }
                        }
                        button.show();
                        return false;
                    },
                });
            }
        });
    }
</script>
