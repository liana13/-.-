<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Regformadmin;

$registeradminform = new Regformadmin;
 ?>
<div class="register">
    <?php $form = ActiveForm::begin([
        'id'=>'regadminform',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action'=>['/site/registrationadmin'],
    ]); ?>

        <?= $form->field($registeradminform, 'username')->textInput(['placeholder' => "Введите логин ..."])->label("Придумайте уникальный логин, это может быть сочетание букв, цифр, или букв и цифр") ?>

        <?= $form->field($registeradminform, 'email')->textInput(['placeholder' => "Введите эл. почту ..."])->label('Введите название электронной почты, на которую Вам придет ссылка для подтверждения регистрации') ?>

        <?= $form->field($registeradminform, 'newpassword')->passwordInput(['placeholder' => "Введите пароль ..."])->label("Введите пароль, это может быть сочетание букв, цифр, или букв и цифр") ?>

        <?= $form->field($registeradminform, 'passwordconfirm')->passwordInput(['placeholder' => "Повторите пароль ..."])->label(false) ?>

        <?= $form->field($registeradminform, 'type')->hiddenInput(['value' => 3])->label(false) ?>

        <div class="form-group text-center">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-login btn-common', 'name' => 'reg-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
