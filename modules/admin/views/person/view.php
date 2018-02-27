<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Person;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Администраторы объектов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'user_id',
            'login',
            'email',
            [
                'label' => 'Дата рег.',
                'attribute' => 'user_id',
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
            'name_org_1:ntext',
            'name_org_2:ntext',
            'address:ntext',
            'inn:ntext',
            'phone:ntext',
            'fio:ntext',
            'address_mestozhitelstvo:ntext',
            'tphone',
            'email:email',
            'mails',
        ],
    ]) ?>

</div>
