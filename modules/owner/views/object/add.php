<?php

use yii\helpers\Html;
use app\models\Tarif;

/* @var $this yii\web\View */
/* @var $model app\models\Object */

$this->title = Yii::t('app', 'Добавить объект');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Мои объекты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$tarifid = Tarif::findOne(['id'=>Yii::$app->request->get('id')])->tarifid;
?>
<div class="object-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php if ($tarifid == 0): ?>
        <?= $this->render('_addfree', [
            'model' => $model,
        ]) ?>
    <?php elseif($tarifid == 4): ?>
        <?= $this->render('_addonline', [
            'model' => $model,
        ]) ?>
    <?php else: ?>
        <?= $this->render('_addtarif', [
            'model' => $model,
        ]) ?>
    <?php endif; ?>


</div>
