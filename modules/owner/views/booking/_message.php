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
$user_obj = $model->user_id;
$mid = $model->id;
?>
<?php
$js = "setInterval(function(){
    $.post('/site/getdialog?object_id=$object&uid=$user_obj', function(data) {
        $('input#dialog-$mid').val(data);
     });
},1000)";
$this->registerJs($js);
?>
<div class="row">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['id' => 'message-form', 'action' => ['/owner/booking/message', 'id'=>$object]]); ?>
            <?= $form->field($message, 'text')->textarea(['rows' => 4])->label('Введите Ваше сообщение или вопрос') ?>
            <?= $form->field($message, 'user_one')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false) ?>
            <?= $form->field($message, 'user_two')->hiddenInput(['value' => $user_obj])->label(false) ?>
            <?= $form->field($message, 'dialogue_id')->hiddenInput(['id'=>'dialog-'.$model->id])->label(false) ?>
            <?= $form->field($message, 'object_id')->hiddenInput(['value' => $object])->label(false) ?>
            <?= $form->field($message, 'status')->hiddenInput(['value' => 0])->label(false) ?>

            <div class="form-group col-sm-12 text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-common', 'name' => 'contact-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
