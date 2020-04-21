<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 11.02.2019
 * Time: 10:04
 */
/* @var $model \modules\utils\models\UtilsShare */
?>
    <aside class="text-box--aside">
        <div class="sharing">
            <ul>
                <li>
                    <a href="http://twitter.com/share?url=<?= Yii::$app->request->getAbsoluteUrl() ?>"
                       class="link social-link link--tw" data-social="tw">
                        <i class="ico">
                            <svg xmlns="http://www.w3.org/2000/svg">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xlink:href="#icon-share-twitter"/>
                            </svg>
                        </i>
                        <span class="count"><?= $model->tw ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/sharer.php?u=<?= Yii::$app->request->getAbsoluteUrl() ?>"
                       class="link social-link link--fb" data-social="fb">
                        <i class="ico">
                            <svg xmlns="http://www.w3.org/2000/svg">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xlink:href="#icon-share-facebook"/>
                            </svg>
                        </i>
                        <span class="count"><?= $model->fb ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://vk.com/share.php?url=<?= Yii::$app->request->getAbsoluteUrl() ?>"
                       class="link social-link link--vk" data-social="vk">
                        <i class="ico">
                            <svg xmlns="http://www.w3.org/2000/svg">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xlink:href="#icon-share-vk"/>
                            </svg>
                        </i>
                        <span class="count"><?= $model->vk ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://connect.ok.ru/offer?url=<?= Yii::$app->request->getAbsoluteUrl() ?>"
                       class="link social-link link--ok" data-social="ok">
                        <i class="ico">
                            <svg xmlns="http://www.w3.org/2000/svg">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                     xlink:href="#icon-share-ok"/>
                            </svg>
                        </i>
                        <span class="count"><?= $model->ok ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
<?php
$js = <<<JS
let urls = {
    vk:'https://vk.com/share.php?url=',
    ok:'https://connect.ok.ru/offer?url=',
    fb:'https://www.facebook.com/sharer.php?u=',
    tw:'http://twitter.com/share?url=',
};
$(document).on('click', 'a.social-link', function (e) {
    e.preventDefault();
    let button = $(this);
    let url = document.location.pathname;
    let social = button.data('social');
    let shareUrl = urls[social]+document.location.href;
    $.post('/feedback/share', {social:social, page:url}, function (data){
        if(data.status==='success'){
            button.find('span.count').text(data.count);
        }
    });
    window.open(shareUrl,'','toolbar=0,status=0,width=626,height=436');
});
JS;
$this->registerJs($js);
?>