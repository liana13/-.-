<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use app\models\Booking;
use app\models\Object;
use app\models\search\CanceledSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CanceledSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Отмененные бронирования');
$this->params['breadcrumbs'][] = $this->title;
$search = new CanceledSearch();
?>
<div class="booking-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="search-form">
        <?php $form = ActiveForm::begin(['action'=>['/admin/booking/canceled'],
        'method' => 'get','options' => ['class' => 'form-inline']]); ?>

            <?= $form->field($search, 'title')->textInput(['class'=>'form-control calendar-search', 'placeholder'=>'Название объекта',
                'value'=>(Yii::$app->request->get('CanceledSearch')['title'])?Yii::$app->request->get('CanceledSearch')['title']:''])->label(false) ?>

            <?= Html::submitButton(Yii::t('app', 'Показать'), ['class' => 'btn btn-primary top']) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <ul class="nav nav-tabs">
        <li><?=Html::a('Бронирования', ['/admin/booking/index'])?></li>
        <li><?=Html::a('Незаезд', ['/admin/booking/incompleted'])?></li>
        <li class="active"><?=Html::a('Отмененные бронирования', ['/admin/booking/canceled/'])?></li>
    </ul>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_item',
        'summary'=>'',
    ]) ?>
</div>
