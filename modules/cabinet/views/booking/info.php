<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Booking;
use app\models\User;
use yii\widgets\ActiveForm;

$model = User::findOne(['id'=>Yii::$app->user->getId()]);
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Мои данные для бронирования";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div id="exTab2">
        <ul class="nav nav-tabs">
			<li><?=Html::a('Будущие поездки', ['/cabinet/booking/future'])?></li>
			<li><?=Html::a('Прошедшие поездки', ['/cabinet/booking/past'])?></li>
            <li><?=Html::a('Отмененные поездки', ['/cabinet/booking/cancel'])?></li>
            <li class="active"><?=Html::a('Мои данные для бронирования', ['/cabinet/booking/info'])?></li>
		</ul>
		<div class="tab-content">
            <div class="tab-pane active">
                <div class="shadow-out">
                    <h5>Вы можете заполнить эти данные, чтобы не заполнять их каждый раз при осуществлении бронирования. Эти данные будут видны только администратору забронированного Вами объекта размещения</h5>
                    <?php $form = ActiveForm::begin(); ?>
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Имя, Фамилия'); ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Сохранить'): Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-common': 'btn btn-common']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
		</div>
    </div>
</div>
