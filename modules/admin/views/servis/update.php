<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Servis */

$this->title = Yii::t('app', 'Редактировать');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сервисы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="servis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
