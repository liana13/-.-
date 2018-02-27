<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\User;
use app\models\Price;
use app\models\Childage;
use app\models\Curency;
use dosamigos\multiselect\MultiSelect;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booking-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-common']) ?>
    </div>
    <?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(User::find()->all(),'id','username'),
       ['prompt' => 'Выберите пользователя'])->label(false) ?>


   <?= $form->field($model, 'object_id')->dropDownList(
        ArrayHelper::map(Object::find()->all(),'id','title'),
       ['prompt' => 'Выберите объект'])->label(false) ?>

   <?= $form->field($model, 'catroom_id')->dropDownList(
        ArrayHelper::map(Catroom::find()->all(),'id','title'),
       ['prompt' => 'Выберите номер'])->label(false) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'from')->Input('date') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'to')->Input('date') ?>
        </div>
    </div>
    <?php  $list = ['0'=> 'Не подтверждено', '1' => 'Подтверждено' ]; ?>

    <?= $form->field($model, 'status')
                        ->dropDownList($list, ['prompt' => 'Выберите', 'value'=>1]);?>

    <?= $form->field($model, 'price')->dropDownList(
         ArrayHelper::map(Price::find()->all(),'id','price'),
        ['prompt' => 'Выберите цена']) ?>

    <?= $form->field($model, 'adult_count')->textInput() ?>

    <?= $form->field($model, 'child_count')->textInput() ?>

    <?= $form->field($model, 'childs_ages')->widget(MultiSelect::classname(),[
                            'id'=>"spec",
                            "options" => ['multiple'=>"multiple"],
                            'data' => [  ArrayHelper::map(Childage::find()->all(),'id','child_age'),['prompt' => 'Выбрать всё']],
                            'name' => 'multti',
                            "clientOptions" =>
                                [
                                    "includeSelectAllOption" => false,
                                    'numberDisplayed' => 3
                                ],
                    ]);?>

    <?= $form->field($model, 'currency_id')->dropDownList(
             ArrayHelper::map(Curency::find()->all(),'id','title'),
            ['prompt' => 'Выберите валюта']) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'arrival_time')->Input('date') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'departure_time')->Input('date') ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-common']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
