<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="grid-viewobject">
        <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                  'id',
                  'username',
                  [
                      'label' => 'Активность',
                      'attribute' => 'status',
                      'format' => 'html',
                      'value' => function($data){
                          if ($data->type!=1) {
                              if ($data->status == 10) {
                                  return "<a href='".Yii::$app->request->baseUrl."/admin/user/deactivate/".$data->id."' title='Нажмите для переключения состояния.' class='text-success'><b>Да</b><a>";
                              } else {
                                  return "<a href='".Yii::$app->request->baseUrl."/admin/user/activate/".$data->id."' title='Нажмите для переключения состояния.' class='text-danger'><b>Нет</b><a>";
                              }
                          } else {
                              return '';
                          }
                      },
                  ],
                  'email',
                 [
                    'label' => 'Дата последнего входа',
                    'attribute' => 'lastvisited_at',
                    'value' => function($data){
                        if ($data->lastvisited_at == NULL) {
                            return "Никогда";
                        } else {
                            return $data->lastvisited_at;
                        }
                    },
                ],
                'created_at',

                 ['class' => 'yii\grid\ActionColumn', 'template' => '{update}{delete}'],
              ],
          ]); ?>
    </div>
</div>
