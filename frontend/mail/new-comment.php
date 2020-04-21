<?php
/* @var $model \modules\blog\models\Comment */
?>
<tr>
    <td align="center" style="padding-top: 52px;">
        <table width="89%" border="0" cellspacing="0" cellpadding="0" style="text-align: left;">
            <tbody>
            <tr>
                <td style="padding-bottom: 38px; font-size: 24px; line-height: 24px;  font-family: Arial, sans-serif;  color:#1e1435; text-align: left;">
                    На ваш комментарий получен ответ!
                </td>
            </tr>
            <tr>
                <td style="color:#1e1435;  font-family: Verdana, Arial, sans-serif; font-size:14px; line-height: 24px;">
                    Вы писали: <?= $model->parent->text ?>
                </td>
            </tr>
            <tr>
                <td style="color:#1e1435;  font-family: Verdana, Arial, sans-serif; font-size:14px; line-height: 24px;">
                    <?= $model->name ?> ответил: <?= $model->text ?>
                </td>
            </tr>
            <tr>
                <td style="color:#1e1435;  font-family: Verdana, Arial, sans-serif; font-size:14px; line-height: 24px;">
                    <?= \yii\helpers\Html::a('Перейти на страницу', Yii::$app->params['front'] . '/blog/' . $model->post->slug) ?>
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
