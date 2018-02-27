<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $contact app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\ContactForm;
use app\models\User;
use app\models\Properties;
use app\models\Servis;

$contact = new ContactForm;
if (Properties::findOne(['object_id' => $model->id, 'field_id'=>38])->field_value) {
    $objectemail = Properties::findOne(['object_id' => $model->id, 'field_id'=>38])->field_value;
}
if (!Yii::$app->user->isGuest) {
    $fio = User::findOne(['id'=>Yii::$app->user->getId()])->name;
    $email = User::findOne(['id'=>Yii::$app->user->getId()])->email;
    $phone = User::findOne(['id'=>Yii::$app->user->getId()])->phone;
} else {
    $fio = "";
    $email = "";
    $phone = "";
}
$service = implode(' ', explode('-', Servis::findOne(['id'=>$model->service])->aliastwo));
$object = $service." ".$model->title;
$url = Yii::$app->urlManager->createAbsoluteUrl(['/'.$model->alias]);
?>
<div class="site-contact">
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'contact-form', 'action' => ['/site/contact']]); ?>
            <?= $form->field($contact, 'objectemail')->hiddenInput(['value' => ($objectemail)?$objectemail:''])->label(false)?>
            <?= $form->field($contact, 'object')->hiddenInput(['value' => $object])->label(false)?>
            <?= $form->field($contact, 'url')->hiddenInput(['value' => $url])->label(false)?>
            <?= $form->field($contact, 'obj')->hiddenInput(['value' => $model->id])->label(false)?>
            <div class="col-sm-6">
                <?= $form->field($contact, 'name')->textInput(['value'=>$fio]) ?>
                <?= $form->field($contact, 'count')->input('number') ?>
                <?= $form->field($contact, 'from')->input('date') ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($contact, 'phone')->textInput(['value'=>$phone]) ?>
                <?= $form->field($contact, 'email')->textInput(['value'=>$email]) ?>
                <?= $form->field($contact, 'to')->input('date') ?>
            </div>
            <div class="col-sm-12">
                <?= $form->field($contact, 'info')->textarea() ?>
            </div>
            <div class="form-group col-sm-12 text-center">
                <?= Html::submitButton('Забронировать', ['class' => 'btn btn-common', 'name' => 'contact-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
