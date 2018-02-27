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

$message = new Message;
$user_obj = Object::findOne(['id'=>$object])->user_id;
?>
<?php
$js = "setInterval(function(){
    $.post('/site/getdialog?object_id=$object&uid=$user_obj', function(data) {
        $('input#message-dialogue_id').val(data);
     });
},1000)";
$this->registerJs($js);
?>
<div class="row">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['id' => 'message-form', 'action' => ['/site/message', 'id'=>$object]]); ?>
            <?= $form->field($message, 'user_one')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false) ?>
            <?= $form->field($message, 'user_two')->hiddenInput(['value' => $user_obj])->label(false) ?>
            <?= $form->field($message, 'dialogue_id')->hiddenInput()->label(false) ?>
            <?= $form->field($message, 'object_id')->hiddenInput(['value' => $object])->label(false) ?>
            <?= $form->field($message, 'status')->hiddenInput(['value' => 0])->label(false) ?>
            <div class="col-sm-12">
                <?php if (Object::findOne(['id'=>$object])->tarif_id!=4): ?>
                    <?= $form->field($message, 'contact')->textInput()->label('Напишите Ваш e-mail или номер телефона для ответа') ?>
                <?php endif; ?>
                <?= $form->field($message, 'text')->textarea(['rows' => 4])->label('Введите Ваше сообщение или вопрос') ?>
            </div>
            <div class="form-group col-sm-12 text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-common', 'name' => 'contact-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
