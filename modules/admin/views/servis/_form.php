<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Servis;
use pendalf89\filemanager\widgets\TinyMCE;

/* @var $this yii\web\View */
/* @var $model app\models\Servis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servis-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-6">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'aliastwo')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'parent_id')->dropDownList(
             ArrayHelper::map(Servis::find()->where(['parent_id'=>0])->all(),'id','title'),
            ['prompt' => 'Выберите родительскую категорию']) ?>

        <?= $form->field($model, 'sort')->input('number') ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'description')->widget(TinyMCE::className(), [
           'clientOptions' => [
               'menubar' => false,
               'height' => 300,
               // 'data-url-info' => '/uploads/images',
               'image_dimensions' => false,
               'plugins' => [
                   'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table',
               ],
               'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
           ],
       ]); ?>
    </div>
    <div class="col-sm-12 form-group text-right">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
