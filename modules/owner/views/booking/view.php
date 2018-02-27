<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Booking;
/* @var $this yii\web\View */
/* @var $model app\models\Booking */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Бронирования'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-common']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот элемент?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Имя пользователя',
                'attribute' => 'user_id',
                'value' => function($data){
                    return Booking::get_message_user($data->user_id);
                },
              ],
            [
              'label' => 'Объект',
              'attribute' => 'object_id',
              'value' => function($data){
                  return Booking::get_message_object($data->object_id);
              },
            ],
            [
                'label' => 'Номер',
                'attribute' => 'room_id',
                'value' => function($data){
                    return Booking::get_message_room($data->room_id);
                },
              ],
            'from',
            'to',
            'status',
            'price',
            'adult_count',
            'child_count',
            'childs_ages',
            [
                'label' => 'Валюта',
                'attribute' => 'currency_id',
                'value' => function($data){
                    return Booking::get_message_currency($data->currency_id);
                },
              ],
            'arrival_time',
            'departure_time',
        ],
    ]) ?>

</div>
