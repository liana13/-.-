<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use app\models\LoginForm;
use app\models\User;
use app\models\PasswordResetRequestForm;
use app\models\Regform;
use app\models\Regformadmin;
use app\models\Social;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css" media="print">
    @page {size:  auto; margin: 0mm;}
    html {background-color: #FFFFFF; margin: 0px;}
    body {margin: 0mm 15mm 0mm 15mm;}
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
