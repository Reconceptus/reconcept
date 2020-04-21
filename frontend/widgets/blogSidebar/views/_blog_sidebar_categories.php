<?php
/**
 * Created by PhpStorm.
 * User: venodon
 * Date: 24.01.2019
 * Time: 12:36
 */
/* @var \modules\blog\models\BlogCategory[] $models */
/* @var $slug string */
?>
<div class="sidebar-nav">
    <ul>
        <li><?= \yii\helpers\Html::a('Все статьи', \yii\helpers\Url::to('/blog'), ['class' => $slug ? '' : 'active']) ?></li>
        <?php foreach ($models as $model): ?>
            <li><?= \yii\helpers\Html::a($model->name, \yii\helpers\Url::to('/blog/category/' . $model->slug), ['class' => $slug == $model->slug ? 'active' : '']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
