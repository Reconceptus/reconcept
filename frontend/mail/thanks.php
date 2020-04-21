<?php
/**
 * Created by PhpStorm.
 * User: venod
 * Date: 12.02.2019
 * Time: 17:02
 */

use modules\blog\models\Post;

$models = Post::find()->where(['status' => Post::STATUS_ACTIVE])->andWhere(['to_letter' => 1])->limit(8)->all();
/* @var $models Post[] */
?>
<tr>
    <td align="center" style="padding-top: 52px;">
        <table width="89%" border="0" cellspacing="0" cellpadding="0" style="text-align: left;">
            <tbody>
            <tr>
                <td style="padding-bottom: 38px; font-size: 24px; line-height: 24px;  font-family: Arial, sans-serif;  color:#1e1435; text-align: left;">
                    <?php if (!empty($name)): ?>
                        <?= $name ?>, спасибо за обращение!
                    <?php else: ?>
                        Спасибо за обращение!
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="color:#1e1435;  font-family: Verdana, Arial, sans-serif; font-size:14px; line-height: 24px;">
                    Мы получили вашу заявку и скоро свяжемся с вами. Мы подготовили для вас подборку статей.
                </td>
            </tr>
            </tbody>
        </table>
    </td>
</tr>
<?php foreach ($models as $model): ?>
    <?= $this->render('_letter_post', ['model' => $model]) ?>
<?php endforeach; ?>

