<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Servis */

$this->title = Yii::t('app', 'Добавить сервис');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сервисы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
