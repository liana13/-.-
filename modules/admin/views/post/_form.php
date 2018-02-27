<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use dosamigos\tinymce\TinyMce;
use pendalf89\filemanager\widgets\TinyMCE;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'title')->textInput(['onchange'=>'$.post( "urlpost?id='.'"+$(this).val(), function( data ) {
                      $( "input#urlpost" ).val(data.toLowerCase());
                  });']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'url')->textInput(['id' => "urlpost"]) ?>
        </div>
    </div>

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
    
    <?= $form->field($model, 'keyword')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
