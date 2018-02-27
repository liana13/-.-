<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $message app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use app\models\Message;
use app\models\User;
use app\models\Object;
use app\models\Servis;


$message = new Message;

$alias = Object::findOne(['id'=>$object]);

$service = implode(' ', explode('-', Servis::findOne(['id'=>$alias->service])->aliastwo));
$objecttitle = $service." ".$alias->title;


$url = Yii::$app->urlManager->createAbsoluteUrl(['/'.$alias->alias]);

?>
<div class="row">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['id' => 'message-form', 'action' => ['/site/message', 'id'=>$object]]); ?>
            <div class="col-sm-12">
                <?= $form->field($message, 'objecttitle')->hiddenInput(['value'=>$objecttitle])->label(false) ?>
                <?= $form->field($message, 'url')->hiddenInput(['value'=>$url])->label(false) ?>
                <?= $form->field($message, 'username')->textInput()->label('Напишите Ваше имя') ?>
                <?= $form->field($message, 'contact')->textInput()->label('Напишите Ваш e-mail или номер телефона для ответа') ?>
                <?= $form->field($message, 'text')->textarea(['rows' => 4])->label('Введите Ваше сообщение или вопрос') ?>
            </div>
            <div class="form-group col-sm-12 text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-common', 'name' => 'contact-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
