<?php

use common\models\User;
use frontend\widgets\footer\Footer;
use modules\config\models\Config;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $user User */
/* @var $user2 User */
$email = Config::getValue('studio_email');
$phone = Config::getValue('studio_phone');

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
$contacts = Footer::getContacts();
$cres = [];
foreach ($contacts as $contact) {
    $cres[] = Html::a($contact['name'], Url::to($contact['value']), ['target' => '_blank', 'rel' => 'nofollow']);
}
?>
<div id="main" class="main">
    <div class="text-section">
        <div class="content content--lg">
            <div class="text-section--main">
                <div class="content">
                    <div class="page-title">
                        <h1 class="page-title--text"><?= Config::getValue('contact_page_title') ?></h1>
                    </div>
                </div>
            </div>
            <div class="text-section--main">
                <div class="content content--md">
                    <div class="contact-layout">
                        <aside class="contact-sidebar">
                            <div class="links">
                                <a class="item" target="_blank" href="tel:<?= $phone ?>"><?= $phone ?></a>
                                <a class="item" target="_blank" href="mailto:<?= $email ?>"><?= $email ?></a>
                                <span class="item">
                                     <?= Config::getValue('studio_address') ?>
                                </span>
                            </div>
                            <div class="text">
                                Наши страницы <?= implode(', ', $cres) ?>.
                                <p>ИП Аманова АС</p>
                                <p>ОГРНИП 3123460052200029</p>
                                <p>Безналичный рассчет, рубли, евро.</p>
                            </div>
                        </aside>
                        <div class="contact-main">
                            <div class="photo">
                                <figure>
                                    <div class="rotate-box">
                                        <div class="img">
                                            <img src="<?= $user->image ?>">
                                        </div>
                                    </div>
                                    <figcaption>
                                        <?= $user->position ?>
                                    </figcaption>
                                </figure>
                            </div>
                            <div class="photo">
                                <figure>
                                    <div class="rotate-box">
                                        <div class="img">
                                            <img src="<?= $user2->image ?>" alt="Azaliya">
                                        </div>
                                    </div>
                                    <figcaption>
                                        <?= $user2->position ?>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content content--lg">
        <div class="inline-support">
            <?= \frontend\widgets\forms\Forms::widget(['viewName' => 'support_inline']) ?>
        </div>
    </div>
</div>
