<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\Config;
$this->title = $message;
$logo = Config::findOne(['id'=>1]);
?>
<div class="site-error">
    <?php if ($logo->logo): ?>
        <img src="<?=Yii::$app->request->baseUrl.'/'.$logo->logo?>" alt="logo" width="250" height="250">
        <?php else: ?>
        <h1><?=Yii::$app->name?></h1>
    <?php endif; ?>
    <h2><?=Html::encode($message)?></h2>
    <?= Html::a("Вернутся на сайт",['/'], ['class'=>"title"])?>
</div>
