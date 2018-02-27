<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Review;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Отзывы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-common']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Объект',
                'attribute' => 'object_id',
                'value' => function($data){
                    return Review::get_message_object($data->object_id);
                },
            ],
            [
                  'label' => 'Имя пользователя',
                  'attribute' => 'user_id',
                  'value' => function($data){
                      return Review::get_message_user($data->user_id);
                  },
            ],
            'rate',
            'description:ntext',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
