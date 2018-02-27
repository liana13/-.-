<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;

/* @var $this yii\web\View */
/* @var $model app\models\Coefficient */
/* @var $form yii\widgets\ActiveForm */
$objlist=[];
$objects = Object::find()->where(['tarif_id'=>4])->all();
foreach ($objects as $object) {
    $objlist[$object->id] = $object->full_title;
}
?>

<div class="coefficient-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'object_id')->dropDownList(
                     $objlist,
                    ['prompt' => 'Выберите объект']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'percent')->input('number') ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'interval')->input('number') ?>
        </div>
    </div>
    <?= $form->field($model, 'datefrom')->hiddenInput(['value'=>date('Y-m-d')])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
