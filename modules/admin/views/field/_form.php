<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Tarif;

/* @var $this yii\web\View */
/* @var $model app\models\Field */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="field-form col-sm-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tarif_id')->dropDownList(
             ArrayHelper::map(Tarif::find()->all(),'id','title'),
            ['prompt' => 'Выберите тариф']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->input('number') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
