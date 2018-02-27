<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Usertype;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$list = ['0'=> 'Не активен', '10' => 'активен' ];
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'newpassword')->passwordInput(['maxlength' => true, 'placeholder' => '******']) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->dropDownList($list)->label(false);?>

            <?= $form->field($model, 'type')->hiddenInput(['value' => '3'])->label(false) ?>

        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'created_at')->textInput(['disabled' => true]) ?>

            <?= $form->field($model, 'lastvisited_at')->textInput(['disabled' => true]) ?>

            <?= $form->field($model, 'id')->textInput(['disabled' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Сохранить') : Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
