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

$this->title = Yii::t('app', 'Мои объекты');
$this->params['breadcrumbs'][] = $this->title;
$objects = Object::find()->all();
$model = new Object;
$tarif = new Changetarif;
if (Yii::$app->session->hasFlash('changemytarif')){
    Modal::begin([
        'id'=>'notify-modal',
        'size'=>'modal-sm',
        'header' => '<h2 class="modal-title text-center">Уведомление</h2>',
    ]);?>
    <div id="not-content" class="text-center">
        <p class="text-center">Ваш запрос отправлен администратору.</p>
        <?= Html::button('Ok',['class'=>'btn-close btn btn-common text-center', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
    </div>
    <?php
    Modal::end();
    $jsnotify = "$('#notify-modal').modal('show');$('#notify-modal').find('#not-content').show();";
    $this->registerJs($jsnotify);
}
Modal::begin([
    'id'=>'changetarif',
    'size'=>'modal-sm',
    'header' => '<h2 class="modal-title text-center">Сменить тариф</h2>',
]);?>
<?php $form = ActiveForm::begin([
    'method' => 'post',
    'action'=>['/owner/object/changetarif'],
]); ?>
    <?= $form->field($tarif, 'id')->hiddenInput(['id' => 'objectid'])->label(false) ?>
    <?= $form->field($tarif, 'tarif_id')->dropDownList(ArrayHelper::map(Tarif::find()->all(),'tarifid','title'))->label('Тариф') ?>
    <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-common']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php
Modal::end();
$js = "$('.changetarif').click(function(e){
    var id = $(this).attr('href').split('-')[1];
    $('#objectid').val(id);
    e.preventDefault();$('#changetarif').modal('show');
})";
$this->registerJs($js);
?>
<div class="object-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a(Yii::t('app', 'Добавить'),'object/create', ['class' => 'btn btn-common']) ?></p>

    <div class="grid-viewobject">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                   'attribute' => 'title',
                   'format'=>'raw',
                   'attribute' => 'Название',
                   'value' => function($data){
                       $servicetitle = "";
                       foreach (explode('-', Servis::findOne(['id'=>$data->service])->aliastwo) as $stitle) {
                           $servicetitle .= $stitle . " ";
                       }
                       return Html::a($servicetitle ." ". $data->title, Yii::$app->urlManager->createAbsoluteUrl([$data->alias]),['target'=>'_blank']);
                   },
                ],
                    [
                          'label' => 'Тариф',
                          'attribute' => 'tarif_id',
                          'format' => 'html',
                          'value' => function($data){
                              if ($data->act_oplata != "0") {
                                  return Object::get_message_tarif($data->tarif_id) .
                                  "<br><a href='#modal-".$data->id."' data-toggle='modal' class='changetarif' ><small>Сменить тариф</small><a>";
                              } else {
                                  return "Ждёт подтверждения <br>".Object::get_message_tarif($data->new_tarif) . "<br>с тарифа ".Object::get_message_tarif($data->tarif_id).
                                  "<br><a href='#modal-".$data->id."' data-toggle='modal' class='changetarif' ><small>Сменить тариф</small><a>";
                              }

                          },
                    ],

                    [
                        'label' => 'Оплачено до',
                        'attribute' => 'end_date',
                        'format' => 'html',
                        'value' => function($data){
                            if ($data->end_date != '0000-00-00 00:00:00' && $data->end_date != '0000-00-00' && $data->end_date != '0') {
                                return $data->end_date;
                            } else {
                                return "";
                            }
                        },
                    ],
                    'address',
                    [
                        'label' => 'Активность',
                        'attribute' => 'active',
                        'format' => 'html',
                        'value' => function($data){
                            if ($data->active == 1) {
                                return "<b>Да</b>";
                            } else {
                                return "<b>Нет</b>";
                            }
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
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
              ],
        ]); ?>
    </div>
</div>
