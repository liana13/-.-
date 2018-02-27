<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\User;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Review */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="review-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'object_id')->dropDownList(
         ArrayHelper::map(Object::find()->all(),'id','title'),
        ['prompt' => 'Выберите объект']) ?>

    <?= $form->field($model, 'user_id')->dropDownList(
         ArrayHelper::map(User::find()->all(),'id','username'),
         ['prompt' => 'Выберите пользователя']) ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'description')->widget(TinyMce::className(), [
        'options' => ['rows' => 6],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ]);?>
    <?php  $list = ['0'=> 'Не подтверждено', '1' => 'Подтверждено' ]; ?>

    <?= $form->field($model, 'status')
                        ->dropDownList($list, ['prompt' => 'Выберите', 'value'=>0]);?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-common']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
