<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
if ($user->type==1) {
    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['admin/default/reset-password', 'token' => $user->password_reset_token]);
} else {
    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
}
?>
Здравствуйте <?= Html::encode($user->name) ?>.

Чтобы сбросить пароль, нажмите на ссылку ниже.

<?= $resetLink ?>
