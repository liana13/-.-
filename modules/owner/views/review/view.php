<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Review;

/* @var $this yii\web\View */
/* @var $model app\models\Review */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Отзывы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-view">

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
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
