<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 01.02.2019
 * Time: 11:31
 */
/* @var $policy string */
?>

    <div class="modal search-modal">
        <div class="modal-header">
            <div class="content content--lg">
                <div class="modal-header--actions">
                    <a href="/" class="logo">
                        <img src="/svg/logo.svg" alt="ReConcept">
                    </a>
                    <button class="close">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
        <div class="search-form modal-form">
            <div class="content content--md">
                <form id="blog-search-form" action="<?= \yii\helpers\Url::to('/blog/search') ?>" method="get">
                    <div class="input-box">
                        <input class="search-input" name="q" type="text" placeholder="Поиск по статьям">
                        <div class="input-dublicate">
                        <span class="clone-text">
                            <span class="text"></span>
                            <button class="reset" type="reset">&times;</button>
                        </span>
                        </div>
                        <button class="submit-btn js-search-blog">
                            <i class="ico">
                                <svg xmlns="http://www.w3.org/2000/svg">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xlink:href="#icon-arrow-right-long"/>
                                </svg>
                            </i>
                        </button>
                    </div>
                </form>
                <div class="search-nothing-found" style="color:#FFFFFF; text-align: center; margin-top: 50px; display: none">По вашему запросу ничего не найдено. Попробуйте изменить запрос и поискать еще</div>
            </div>
        </div>
    </div>
<?php
$js = <<<JS
$(document).on('click', '.js-search-blog', function(e) {
    e.preventDefault();
     $('.search-nothing-found').hide();
    let button = $(this);
    let q = $('.search-input[name=q]').val();
    button.hide();
    $.post('/blog/search', {q:q}).done(function(data) {
          if(data.status === 'success'){
              if(!data.results){
                  $('.search-nothing-found').show();
                  button.show();
              }
          }
        });
});
JS;
$this->registerJs($js);
