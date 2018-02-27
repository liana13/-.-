<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\Booking;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Прошедшие поездки";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div id="exTab2">
        <ul class="nav nav-tabs">
			<li><?=Html::a('Будущие поездки', ['/cabinet/booking/future'])?></li>
			<li class="active"><?=Html::a('Прошедшие поездки', ['/cabinet/booking/past'])?></li>
            <li><?=Html::a('Отмененные поездки', ['/cabinet/booking/cancel'])?></li>
            <li><?=Html::a('Мои данные для бронирования', ['/cabinet/booking/info'])?></li>
		</ul>
		<div class="tab-content ">
            <div class="tab-pane active">
                <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => '_past',
                'summary'=>'',
            ]) ?>
            </div>
		</div>
    </div>
</div>
