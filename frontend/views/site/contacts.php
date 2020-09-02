<?php

use frontend\widgets\footer\Footer;
use modules\config\models\Config;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
$email = Config::getValue('studio_email');
$phone = Config::getValue('studio_phone');

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
$contacts = Footer::getContacts();
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
                                Наши страницы
                                <?php foreach ($contacts as $contact): ?>
                                    <li><?= Html::a($contact['name'], Url::to($contact['value']), ['target' => '_blank', 'rel' => 'nofollow']) ?></li>
                                <?php endforeach; ?>.
                            </div>
                        </aside>
                        <div class="contact-main">
                            <div class="photo">
                                <figure>
                                    <div class="rotate-box">
                                        <div class="img">
                                            <img src="<?= Config::getValue('contact_page_user_1_photo') ?>">
                                        </div>
                                    </div>
                                    <figcaption>
                                        <?= Config::getValue('contact_page_user_1_title') ?>
                                    </figcaption>
                                </figure>
                            </div>
                            <div class="photo">
                                <figure>
                                    <div class="rotate-box">
                                        <div class="img">
                                            <img src="<?= Config::getValue('contact_page_user_2_photo') ?>" alt="Azaliya">
                                        </div>
                                    </div>
                                    <figcaption>
                                        <?= Config::getValue('contact_page_user_2_title') ?>
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
