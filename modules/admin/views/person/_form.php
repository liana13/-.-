<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Persontype;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'type')->dropDownList(
                 ArrayHelper::map(Persontype::find()->all(),'id','title'),
                 ['prompt' => 'Выберите тип']) ?>
            <?= $form->field($model, 'name_org_1')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'name_org_2')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'inn')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'fio')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'priming')->textInput() ?>

        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'address')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'address_mestozhitelstvo')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'phone')->textInput(['rows' => 6]) ?>

            <?= $form->field($model, 'tphone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mails')->textInput(['maxlength' => true]) ?>

            <div class="form-group text-right">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
