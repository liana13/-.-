<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Материалы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
               'attribute' => 'description',
               'format' => 'html',
               'label' => 'Описание',
               'value' => function ($data) {
                   return substr(strip_tags($data['description']), 0, 500)."...";
               },
            ],
            [
               'format'=>'raw',
               'attribute' => 'url',
               'value' => function($data){
                   return Html::a($data->url, Yii::$app->urlManager->createAbsoluteUrl([$data->url]),['target'=>'_blank']);
               },
            ],
            'created_at',

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}{delete}'],
        ],
    ]); ?>
</div>
