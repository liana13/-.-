<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rp-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-6">
        <?= $form->field($model, 'page')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'iditem')->textInput() ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'date')->input('date') ?>

        <?= $form->field($model, 'number')->input('number', ['max'=>6]) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
