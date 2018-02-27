<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Review;
use app\models\Object;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Отзывы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Объект',
                'attribute' => 'object_id',
                'format'=>'raw',
                'value' => function($data){
                    return Html::a(Object::get_object_title($data->object_id), Yii::$app->urlManager->createAbsoluteUrl([Object::get_object_alias($data->object_id)]),['target'=>'_blank']);
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
            [
                'label' => 'Статус',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data){
                    if ($data->status == 1) {
                        return "<b class='text-success'>Подтвержден</b>";
                    } else {
                        return "<a href='".Yii::$app->request->baseUrl."/admin/review/activate/".$data->id."' title='Нажмите для подтверждения.' class='text-danger'><b>Не подтвержден</b><a>";
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>
</div>
