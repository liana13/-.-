<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

if ($user->type==1) {
    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['admin/default/reset-password', 'token' => $user->password_reset_token]);
} else {
    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
}
?>
<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->name) ?>.</p>

    <p>Пожалуйста, нажмите ссылку ниже, чтобы сбросить пароль.</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
