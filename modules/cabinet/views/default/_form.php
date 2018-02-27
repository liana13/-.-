<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Usertype;
use app\modules\cabinet\models\PasswordForm;

$user = new PasswordForm;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form shadow-out">
    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(); ?>
            <div class="profile-img">
                <p><b>Загрузить фото аватара</b></p>
                <?php if ($model->avatar): ?>
                    <img class="img-circle" src="<?=Yii::$app->request->baseUrl.'/'.$model->avatar?>">
                <?php else: ?>
                    <img class="img-circle" src="<?=Yii::$app->request->baseUrl?>/images/avatar-user.png">
                <?php endif; ?>
                <?= $form->field($model, 'file')->fileInput(['onchange'=>'this.form.submit()'])->label(false) ?>
            </div>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true])->label("(Ник) Это имя будет видно при написании Вами отзывов и переписке с администратором объекта") ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Сохранить') : Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-common' : 'btn btn-common']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-6">
            <?php $form = ActiveForm::begin([
                'id' => 'user-password-edit',
                'action'=>['/cabinet/default/changepassword'],
            ]); ?>
                <?= $form->field($user, 'newPassword')->passwordInput(['placeholder'=>"Введите пароль ..."])->label('Введите новый пароль') ?>
                <?= $form->field($user, 'newPasswordRepeat')->passwordInput(['placeholder'=>"Повторите пароль ..."])->label('Повторите пароль') ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-common']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
