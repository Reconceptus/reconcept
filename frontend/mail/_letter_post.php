<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 07.03.2019
 * Time: 9:49
 */
/* @var $model \modules\blog\models\Post*/
?>
<tr>
    <td align="center" style="padding-top: 48px;">
        <table width="89%" border="0" cellspacing="0" cellpadding="0" style="text-align: left;">
            <tbody>
            <tr>
                <td style="font-size: 24px; line-height: 24px; padding-bottom: 16px;">
                    <img width="600" title="<?= $model->name ?>"
                         alt="<?= $model->name ?>"
                         src="<?= Yii::$app->request->getHostInfo() . $model->image_preview ?>"
                         style="border:none; max-width: 100%; height: auto; ">
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 12px; font-size: 18px; line-height: 24px;  font-family: Verdana, Arial, sans-serif;  color:#1e1435; text-align: left;">
                    <?= $model->name ?>
                </td>
            </tr>
            <tr>
                <td style="color:#1e1435;  font-family: Verdana, Arial, sans-serif; font-size:14px; line-height: 24px;">
                    <?= strip_tags(mb_substr($model->text, 0, 250)) ?>
                </td>
            </tr>
            <tr>
                <td align="left"
                    style="font-size: 18px; line-height: 18px; padding-top: 18px; font-family: Arial, sans-serif; color:#30343f; text-align: left;">
                    <div><!--[if mso]>
                        <v:rect xmlns:v="urn:schemas-microsoft-com:vml"
                                xmlns:w="urn:schemas-microsoft-com:office:word"
                                href="#" style="height:40px;v-text-anchor:middle;width:112px;" stroke="f"
                                fillcolor="#192476">
                            <w:anchorlock/>
                            <center>
                        <![endif]-->
                        <a href="<?= Yii::$app->request->getHostInfo() . '/blog/' . $model->slug ?>"
                           style="background-color:#192476;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:14px;font-weight:400;line-height:40px;text-align:center;text-decoration:none;width:112px;-webkit-text-size-adjust:none;">Читать</a>
                        <!--[if mso]>
                        </center>
                        </v:rect>
                        <![endif]-->
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
