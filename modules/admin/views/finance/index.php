<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Object;

$this->title = Yii::t('app', 'Финансы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
      <?= Html::a(Yii::t('app', '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Скачать базу'), ['/fexport.php'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'user_id',
            [
              'label' => 'Логин',
              'attribute' => 'login',
              'format' => 'html',
              'value' => function($data){
                  return Object::get_message_user($data->user_id) ;
              },
            ],
            [
              'label' => 'Объект',
              'format'=>'raw',
              'attribute' => 'object_id',
              'value' => function($data){
                  return Html::a(Object::get_object_title($data->object_id), Yii::$app->urlManager->createAbsoluteUrl([Object::get_object_alias($data->object_id)]),['target'=>'_blank']);
              },
            ],
            'price',
            [
              'label' => 'Дата допуска',
              'attribute' => 'active_online',
              'format' => 'html',
              'value' => function($data){
                  return explode(' ',Object::findOne(['id'=>$data->object_id])->act_oplata)[0];
              },
            ],
            'created_at',
            [
                'label' => 'Оплачено',
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data){
                    if ($data->status == 1) {
                        return "<a href='".Yii::$app->request->baseUrl."/admin/finance/deactivate/".$data->id."' title='Нажмите для переключения состояния.' class='text-success'><b>Да</b><a>";
                    } else {
                        return "<a href='".Yii::$app->request->baseUrl."/admin/finance/activate/".$data->id."' title='Нажмите для переключения состояния.' class='text-danger'><b>Нет</b><a>";
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{delete}'],
        ],
    ]); ?>
</div>
