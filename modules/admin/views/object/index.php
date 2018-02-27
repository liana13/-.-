<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Object;
use app\models\Tarif;
use app\models\Locality;
use app\models\Region;
use app\models\Country;
use app\models\Servis;
use app\models\Changetarif;
use app\models\Tarifend;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Объекты');
$this->params['breadcrumbs'][] = $this->title;
$objects = Object::find()->all();
$objecttarinf = new Changetarif;
$end = new Tarifend;

Modal::begin([
    'id'=>'changetarif',
    'size'=>'modal-sm',
    'header' => '<h2 class="modal-title text-center">Сменить тариф</h2>',
]);?>
<?php $form1 = ActiveForm::begin([
    'action'=>['/admin/object/changetarif'],
]); ?>
    <?= $form1->field($objecttarinf, 'id')->hiddenInput(['id' => 'objectid'])->label(false) ?>
    <?= $form1->field($objecttarinf, 'tarif_id')->dropDownList(ArrayHelper::map(Tarif::find()->all(),'tarifid','title'))->label("Тариф") ?>
    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php
Modal::end();
Modal::begin([
    'id'=>'addend',
    'size'=>'modal-sm',
    'header' => '<h2 class="modal-title text-center">Добавить дату олаты</h2>',
]);?>
<?php $form = ActiveForm::begin([
    'action'=>['/admin/object/addend'],
]); ?>
    <?= $form->field($end, 'id')->hiddenInput(['id' => 'objectidend'])->label(false) ?>
    <?= $form->field($end, 'end',[
        'template' => "{label}{input}<i class='fa fa-calendar text-primary fromicon' aria-hidden='true'></i>{error}{hint}"
        ])->textInput(['class'=>'datefrom form-control calendar-search', 'readonly' => true])->label(false) ?>
    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php
Modal::end();
$js = "$('.changetarif').click(function(e){
    var id = $(this).attr('href').split('-')[1];
    $('#objectid').val(id);
    e.preventDefault();$('#changetarif').modal('show');
})
$('.addend').click(function(e){
    var id = $(this).attr('href').split('-')[1];
    $('#objectidend').val(id);
    e.preventDefault();$('#addend').modal('show');
})";
$this->registerJs($js);
?>
<div class="object-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
      <?= Html::a(Yii::t('app', '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Скачать базу'), ['/export.php'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Аккаунт',
                'attribute' => 'user_id',
            ],
            'id',
            'login',
            [
                  'label' => 'Тариф',
                  'attribute' => 'tarif_id',
                  'format' => 'html',
                  'value' => function($data){
                      return ($data->act_oplata == "0")? ("Подана заявка на ".Object::get_message_tarif($data->new_tarif) . "<br>с тарифа ".Object::get_message_tarif($data->tarif_id).
                      "<br><a href='".Yii::$app->request->baseUrl."/admin/object/changeend/".$data->id."'><small>Подтвердить</small><a>"):
                      (Object::get_message_tarif($data->tarif_id) .
                      "<br><a href='#modal-".$data->id."' data-toggle='modal' class='changetarif' ><small>Сменить тариф</small><a>");
                  },
            ],
            [
                'attribute' => 'title',
                'format'=>'raw',
                'value' => function($data){
                    $servicetitle = "";
                    foreach (explode('-', Servis::findOne(['id'=>$data->service])->aliastwo) as $stitle) {
                        $servicetitle .= $stitle . " ";
                    }
                    return Html::a($servicetitle ." ". $data->title, [Yii::$app->request->baseUrl."/admin/object/viewobject/".$data->id],['target'=>'_blank']);
                },
            ],
            [
                'label' => 'Оплачено до',
                'attribute' => 'end_date',
                'format' => 'html',
                'value' => function($data){
                    if ($data->end_date != '0000-00-00 00:00:00' && $data->end_date != '0000-00-00' && $data->end_date != '0') {
                        return $data->end_date .
                        "<br><a href='".Yii::$app->request->baseUrl."/admin/object/changeend/".$data->id."'><small>Продлить</small><a><br><a href='#modal-".$data->id."' data-toggle='modal' class='addend' ><small>Изменить</small><a>";
                    } else {
                        return "<a href='#modal-".$data->id."' data-toggle='modal' class='addend' ><small>Изменить</small><a>";
                    }
                },
            ],

            [
                'label' => 'Активность',
                'attribute' => 'active',
                'format' => 'html',
                'value' => function($data){
                    if ($data->active == 1) {
                        return "<a href='".Yii::$app->request->baseUrl."/admin/object/deactivate/".$data->id."' title='Нажмите для переключения состояния.' class='text-success'><b>Да</b><a>";
                    } else {
                        return "<a href='".Yii::$app->request->baseUrl."/admin/object/activate/".$data->id."' title='Нажмите для переключения состояния.' class='text-danger'><b>Нет</b><a>";
                    }
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a(
                            '<i class="fa fa-eye" aria-hidden="true"></i>',
                            Yii::$app->request->baseUrl.'/admin/object/viewobject/'.explode("?id=",$url)[1],[
                                'title' => 'Смотреть на сайте','target'=>'_blank'
                            ]
                        );
                    },
                    'update' => function ($url) {
                        return Html::a(
                            '<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>',
                            Yii::$app->request->baseUrl.'/update/'.Object::findOne(['id'=>explode("?id=",$url)[1]])->alias,[
                                'title' => 'Редактировать','target'=>'_blank'
                            ]
                        );
                    },
                ],
            ],
      ] ?>

    <div class="filter-dox">
        <span>Показать</span>
        <?= Html::a("Все", ['/admin/object/index']) ?>
        <?= Html::a("Бесплатные", ['/admin/object?ObjectSearch%5Btarif_id%5D=Бесплатные']) ?>
        <?= Html::a("Тариф №1", ['/admin/object?ObjectSearch%5Btarif_id%5D=Тариф №1']) ?>
        <?= Html::a("Тариф №2", ['/admin/object?ObjectSearch%5Btarif_id%5D=Тариф №2']) ?>
        <?= Html::a("Тариф №3", ['/admin/object?ObjectSearch%5Btarif_id%5D=Тариф №3']) ?>
        <?= Html::a("Онлайн бронирование", ['/admin/object?ObjectSearch%5Btarif_id%5D=Онлайн бронирование']) ?>
        <?= Html::a("Не активные", ['/admin/object?ObjectSearch%5Bactive%5D=0']) ?>
        <?= Html::a("Запрос на смену тарифа", ['/admin/object?ObjectSearch%5Bact_oplata%5D=0']) ?>
        <?= Html::a("Редактированные", ['/admin/object?ObjectSearch%5Bedit%5D=1']) ?>
    </div>

    <div class="grid-viewobject">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
        ]); ?>
    </div>
</div>
