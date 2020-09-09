<?php

use modules\position\models\PositionRequest;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $position int? */
/* @var $depth int? */
/* @var $domain string? */
/* @var $q string? */

$this->title = 'Единичный запрос';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="support-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group ">
        <label class="control-label" for="positionrequest-query">Запрос</label>
        <input type="text" id="positionrequest-query" class="form-control" name="q" maxlength="255" aria-invalid="false" value="<?=$q?>">
    </div>

    <div class="form-group ">
        <label class="control-label" for="positionrequest-domain">Домен</label>
        <input type="text" id="positionrequest-domain" class="form-control" name="domain" maxlength="255" aria-invalid="false" value="<?=$domain?>">
    </div>

    <div class="form-group field-positionrequest-depth has-success">
        <label class="control-label" for="positionrequest-depth">Глубина проверки</label>
        <?= Html::dropDownList('depth', $depth, PositionRequest::DEPTH_LIST, ['class'=>'form-control', 'id'=>'positionrequest-depth']) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if ($position !== null) {
        if ($position === 0) {
            echo '<h3>Домен '.$domain.' не входит в первые '.$depth.' позиций по запросу "'.$q.'"</h3>';
        }else{
            echo '<h3>Домен '.$domain.' находится на '.$position.' позиции по запросу "'.$q.'"</h3>';
        }
    }
    ?>
    <?php Pjax::end(); ?>
</div>
