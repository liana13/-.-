<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\models\Booking;
use app\models\Object;
use app\models\Servis;
use app\modules\owner\models\past;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$printform = new Booking();
$this->title = "Прошедшие бронирования";
$this->params['breadcrumbs'][] = $this->title;
$obj = Yii::$app->request->get('id');
$objects = Object::find()->where(['user_id'=>Yii::$app->user->getId()])->andWhere(['tarif_id'=>4])->all();
$search = new past();
$address = Object::findOne(['id'=>$obj]);
$servicetitle = "";
foreach (explode('-', Servis::findOne(['id'=>$address->service])->aliastwo) as $stitle) {
    $servicetitle .= $stitle . " ";
}
$objtitle = $servicetitle . $address->title;
?>
<?php Modal::begin([
    'id'=>'print-modal',
    'size'=>'modal-sm',
    'header' => '<h3 class="modal-title text-center">Распечатать бронирования</h3>',
]);?>
<div id="regform-content">
    <?php $form = ActiveForm::begin([
        'id'=>'printmodal',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
        'action'=>['/owner/booking/printbron'],
    ]); ?>
        <p>Выберите с какой даты вывести бронирования на печать</p>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($printform, 'objid')->hiddenInput(['value'=>$obj])->label(false) ?>
                <?= $form->field($printform, 'type')->hiddenInput(['value'=>'cancel'])->label(false) ?>
                <?= $form->field($printform, 'from_date',[
                    'template' => "{label}{input}<i class='fa fa-calendar text-primary fromicon' aria-hidden='true'></i>{error}{hint}"
                    ])->textInput(['class'=>'datefrom form-control', 'readonly' => true])->label(false) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <?= Html::submitButton('Распечатать', ['class' => 'btn btn-primary', 'name' => 'print-button']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Modal::end();?>
<div class="booking-index">
    <div class="col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="dropdown">
            <a data-target="#" href="page.html" data-toggle="dropdown" class="dropdown-toggle">Объект: <?=$objtitle.', '.$address->address?> <i class="fa fa-caret-down" aria-hidden="true"></i></a>
            <ul class="dropdown-menu">
                <?php foreach ($objects as $object): ?>
                    <?php $servicetitle = "";
                    foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
                        $servicetitle .= $stitle . " ";
                    }
                    $objtitle = $servicetitle . $object->title . $address->address;?>
                    <li><?=Html::a($objtitle, ['/owner/booking/future/'.$object->id])?></li>
                <?php endforeach; ?>
            </ul>
            <?= Html::Button(Yii::t('app', 'Распечатать бронирования'), ['class' => 'btn btn-primary print_bron']) ?>
        </div>
        <div class="search-form">
            <div class="row">
                <?php $form = ActiveForm::begin(['action'=>['/owner/booking/past/'.$obj],
                'method' => 'get','options' => ['class' => 'search-form form-inline']]); ?>
                    <?= $form->field($search, 'from',[
                        'template' => "{label}{input}<i class='fa fa-calendar text-primary fromicon' aria-hidden='true'></i>{error}{hint}"
                        ])->textInput(['class'=>'datefrom form-control calendar-search', 'readonly' => true,
                        'value'=>(Yii::$app->request->get('past')['from'])?Yii::$app->request->get('past')['from']:''])->label('Показать даты с') ?>

                    <?= $form->field($search, 'to',[
                        'template' => "{label}{input}<i class='fa fa-calendar text-primary toicon' aria-hidden='true'></i>{error}{hint}"
                        ])->textInput(['class'=>'dateto form-control calendar-search', 'readonly' => true,
                        'value'=>(Yii::$app->request->get('past')['to'])?Yii::$app->request->get('past')['to']:''])->label('По') ?>

                    <?= $form->field($search, 'id')->textInput(['class'=>'form-control calendar-search', 'data-toggle'=>'tooltip', 'title'=> 'Введите уникальный номер бронирования или фамилию клиента',
                        'value'=>(Yii::$app->request->get('past')['id'])?Yii::$app->request->get('past')['id']:''])->label('Ключевые слова') ?>

                    <?= Html::submitButton(Yii::t('app', 'Показать'), ['class' => 'btn btn-primary top']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div id="exTab2">
        <ul class="nav nav-tabs">
			<li><?=Html::a('Бронирования', ['/owner/booking/future/'.$obj])?></li>
			<li class="active"><?=Html::a('Прошедшие бронирования', ['/owner/booking/past/'.$obj])?></li>
            <li><?=Html::a('Отмененные бронирования', ['/owner/booking/cancel/'.$obj])?></li>
		</ul>
		<div class="tab-content ">
            <div class="tab-pane active">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => ['class' => 'item'],
                    'itemView' => '_past',
                    'summary'=>'',
                ]) ?>
            </div>
		</div>
    </div>
</div>
<?php
$js = "$('.fromicon').click(function(){
    $('.datefrom').trigger('focus');
});
$('.toicon').click(function(){
    $('.dateto').trigger('focus');
});
$('.datefrom').datepicker({
    language: 'ru',
    format: 'yyyy-mm-dd',
    todayBtn: true,
    autoclose: true,
    todayHighlight: true
});
$('.dateto').datepicker({
    language: 'ru',
    format: 'yyyy-mm-dd',
    todayBtn: true,
    autoclose: true,
    todayHighlight: true
});";
 $this->registerJs($js);
?>
