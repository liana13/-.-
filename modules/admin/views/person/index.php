<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\models\Person;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Администраторы объектов');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="top-scroll">
    	<div class="top-scrollbar"></div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'user_id',
            'login',
            'email',
            [
                'label' => 'Дата рег.',
                'attribute' => 'type',
                'value' => function($data){
                    return User::get_message_regdate($data->user_id);
                },
            ],
            [
                'label' => 'ИП',
                'attribute' => 'type',
                'value' => function($data){
                    if ($data->type == 2) {
                        return $data->fio;
                    } else {
                        return "";
                    }
                },
            ],
            [
                'label' => 'Тип',
                'attribute' => 'type',
                'value' => function($data){
                    return Person::get_message_type($data->type);
                },
            ],
            'name_org_1',
            'name_org_2',
            'inn',
            'fio',
            'phone',
            'address',
            'address_mestozhitelstvo',
            'priming',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a(
                            '<i class="fa fa-eye" aria-hidden="true"></i>',
                            'object?ObjectSearch%5Buser_id%5D='.Person::findOne(['id'=>explode("?id=",$url)[1]])->user_id,[
                                'title' => 'Объекты'
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
