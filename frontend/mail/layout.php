<?php

use modules\config\models\Config;

/* @var $content*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!--<![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReConcept</title>
    <style type="text/css">
        .ExternalClass {
            width: 100%;
        }

        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
            line-height: 100%;
        }

        body {
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }

        body {
            margin: 0;
            padding: 0;
        }

        table td {
            border-collapse: collapse;
        }

        p {
            margin: 0;
            padding: 0;
            margin-bottom: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #21202e;
            line-height: 100%;
        }

        a, a:link {
            color: #932064;
            text-decoration: none;
        }

        body, #body_style {
            background: #ffffff;
            color: #1e1435;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        span.yshortcuts {
            color: #1e1435;
            background-color: none;
            border: none;
        }

        span.yshortcuts:hover,
        span.yshortcuts:active,
        span.yshortcuts:focus {
            color: #1e1435;
            background-color: none;
            border: none;
        }

        a:visited {
            color: #932064;
            text-decoration: none
        }

        a:focus {
            color: #932064;
            text-decoration: underline
        }

        a:hover {
            color: #932064;
            text-decoration: underline
        }

        @media only screen and (max-device-width: 480px) {
            body[yahoo] #container1 {
                display: block !important
            }

            body[yahoo] p {
                font-size: 10px
            }
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
            body[yahoo] #container1 {
                display: block !important
            }

            body[yahoo] p {
                font-size: 12px
            }
        }

        /*@media only screen and (max-width: 640px), only screen and (max-device-width: 640px) {
            div[class=hide-menu], td[class=hide-menu] {
                height: 0 !important;
                max-height: 0 !important;
                display: none !important;
                visibility: hidden !important;
            }
        }*/
    </style>
</head>
<?php
$email = Config::getValue('studio_email');
$host = Yii::$app->params['front'];
?>
<body style="color:#1e1435; font-family: Arial, sans-serif; font-size:16px; background:#ffffff; " alink="#932064"
      link="#932064" bgcolor="#ffffff" text="#1e1435" yahoo="fix">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td valign="top" align="center" width="600" style="width: 600px;">
            <!--[if gte mso 10]>
            <table align="center" width="600" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
            <![endif]-->

            <table width="600" cellspacing="0" cellpadding="0" style="width: 100%; max-width: 600px; min-width: 320px;">
                <tr>
                    <td bgcolor="#ffffff"
                        style="border-bottom-style:solid; border-bottom-width: 8px; border-bottom-color: #f9c582; ">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 45px; border-left-style:solid; border-left-width: 1px; border-left-color: #F0F1F3; border-right-style:solid; border-right-width: 1px; border-right-color: #F0F1F3; border-bottom-style:solid; border-bottom-width: 1px; border-bottom-color: #F0F1F3;">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td align="center">
                                                <div style="display: inline-block; width: 65%; vertical-align: top; min-width: 340px; float: left;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                           style="border-spacing:0;">
                                                        <tr>
                                                            <td style="height: 64px; font-size: 64px; line-height: 64px; width: 225px;">
                                                                <a href="<?= $host ?>"
                                                                   style="color: #1e1435; text-decoration: none; display:block; max-width: 225px;"
                                                                   target="_blank">
                                                                    <img width="225" height="63" title="ReConcept"
                                                                         alt="ReConcept"
                                                                         src="<?= $host ?>/img/logo.jpg"
                                                                         style="border:none; max-width: 225px; height: auto; max-height: 63px;">
                                                                </a>
                                                            </td>
                                                            <td style="width: 15px"></td>
                                                            <td style="height: 64px; font-size: 12px; line-height: 16px; font-family: Arial, sans-serif; text-align: left; color: #1e1435">
                                                                САЙТЫ 5-ГО <br>ПОКОЛЕНИЯ
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div style="display: inline-block; width: 35%; vertical-align: top; min-width: 165px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                           style="border-spacing:0;">
                                                        <tr>
                                                            <td style="height: 64px; font-size: 64px; line-height: 64px;"
                                                                align="right">
                                                                <table align="right" cellspacing="0" cellpadding="0"
                                                                       border="0">
                                                                    <tr>
                                                                        <td style="width: 15px"></td>
                                                                        <td style="width: 32px; height: 32px; line-height: 32px;">
                                                                            <a href="<?= Config::getValue('studio_social_fb') ?>"
                                                                               style="color: #fff; text-decoration: none; display:block; max-width: 32px;"
                                                                               target="_blank">
                                                                                <img width="32" height="32" title="fb"
                                                                                     alt="fb"
                                                                                     src="<?= $host ?>/img/letter/share_fb.png"
                                                                                     style="border:none; max-width: 32px; height: 32px; max-height: 32px;">
                                                                            </a>
                                                                        </td>
                                                                        <td style="width: 15px"></td>
                                                                        <td style="width: 32px; height: 32px; line-height: 32px;">
                                                                            <a href="<?= Config::getValue('studio_social_vk') ?>"
                                                                               style="color: #fff; text-decoration: none; display:block; max-width: 32px;"
                                                                               target="_blank">
                                                                                <img width="32" height="32" title="vk"
                                                                                     alt="vk"
                                                                                     src="<?= $host ?>/img/letter/share_vk.png"
                                                                                     style="border:none; max-width: 32px; height: 32px; max-height: 32px;">
                                                                            </a>
                                                                        </td>
                                                                        <td style="width: 15px"></td>
                                                                        <td style="width: 32px; height: 32px; line-height: 32px;">
                                                                            <a href="<?= Config::getValue('studio_social_ig') ?>"
                                                                               style="color: #fff; text-decoration: none; display:block; max-width: 32px;"
                                                                               target="_blank">
                                                                                <img width="32" height="32" title="ok"
                                                                                     alt="ok"
                                                                                     src="<?= $host ?>/img/letter/share_in.png"
                                                                                     style="border:none; max-width: 32px; height: 32px; max-height: 32px;">
                                                                            </a>
                                                                        </td>
                                                                        <td style="width: 30px"></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <?= $content ?>
                                        <tr>
                                            <td align="center" style="padding-top: 42px;">
                                                <table width="89%" border="0" cellspacing="0" cellpadding="0"
                                                       style="text-align: center;">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 45%;" valign="top">
                                                            <table width="100%" cellspacing="0" cellpadding="0"
                                                                   border="0">
                                                                <tr>
                                                                    <td style="font-size: 14px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left; font-weight: 600;">
                                                                        ReConcept
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size: 14px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left;">
                                                                        Экспертно создаём сайты,
                                                                        интернет-магазины
                                                                        и удобные интерфейсы
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td style="width: 10%;"></td>
                                                        <td style="width: 45%;" valign="top">
                                                            <table width="100%" cellspacing="0" cellpadding="0"
                                                                   border="0">
                                                                <tr>
                                                                    <td style="font-size: 14px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left; font-weight: 600;">
                                                                        Контакты
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size: 14px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left;">
                                                                        <?= Config::getValue('studio_phone') ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size: 14px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left;">
                                                                        <a href="mailto:<?= $email ?>"
                                                                           style="color:#1e1435; text-decoration: none;"><?= $email ?></a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-size: 14px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left;">
                                                                        <a href="mailto:reconcept@mail.ru"
                                                                           style="color:#1e1435; text-decoration: none;">reconcept.ru</a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>


            </table>

            <!--[if gte mso 10]>
            </td></tr></table>
            <![endif]-->
        </td>
    </tr>
</table>
</body>
</html>
