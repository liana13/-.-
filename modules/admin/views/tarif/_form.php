<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Tarif */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'price')->textInput() ?>

            <?= $form->field($model, 'time')->textInput(['maxlength' => true]) ?>
            <?php if ($model->id == 1): ?>
                <?= $form->field($model, 'photo')->input('number') ?>
            <?php elseif ($model->id != 1 && $model->id != 5): ?>
                <span class="span_do">до </span>
                <div class="col-sm-4">
                    <?= $form->field($model, 'photo')->input('number') ?>
                </div>
            <?php else: ?>
                <span class="span_do">до </span>
                <div class="col-sm-2">
                    <?= $form->field($model, 'photodo')->input('number', ['value'=>explode(",", $model->photo)[0]])->label(" ") ?>
                </div>
                <span class="span_do">фото+по </span>
                <div class="col-sm-2">
                    <?= $form->field($model, 'photopo')->input('number', ['value'=>explode(",", $model->photo)[1]])->label(" ") ?>
                </div>
                <span class="span_do"> фото каждого номера</span>
            <?php endif; ?>
        </div>
        <div class="col-sm-6">
            <span class="span_do">До </span>
            <div class="col-sm-5">
                <?= $form->field($model, 'text')->input('number')->label("Если поле оставить незаполненным, будет не ограничено.");?>
            </div>
            <span class="span_do"> символов</span>
            <div class="form-group text-right">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
