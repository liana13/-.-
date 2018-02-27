<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Coefficient;
use app\models\Object;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CoefficientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Коэффициенты');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coefficient-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Объект',
                'format'=>'raw',
                'attribute' => 'object_id',
                'value' => function($data){
                    return Html::a(Object::get_object_title($data->object_id), Yii::$app->urlManager->createAbsoluteUrl([Object::get_object_alias($data->object_id)]),['target'=>'_blank']);
                },
            ],
            'interval',
            'percent',
            'datefrom',

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>
</div>
