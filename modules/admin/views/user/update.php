<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Редактировать');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($model->type == 1): ?>
        <?= $this->render('_formadmin', [
            'model' => $model,
        ]) ?>
    <?php else: ?>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    <?php endif; ?>
</div>
