<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use dmstr\widgets\Alert;
use app\models\Object;
use app\models\Catroom;
use app\models\Room;
use app\models\Calendar;
use app\models\Price;
use app\models\Addprice;
use app\models\Discount;
use app\models\Freeroom;
use app\models\Weekdays;

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppOwnerobjectAsset::register($this);
}
dmstr\web\AdminLteAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$this->title = "Календарь и цены";
$this->params['breadcrumbs'][] = $this->title;

// Price
$freeroom = new Freeroom();
$discountnew = New Discount();
$price = New Price();
$addprice = New Addprice();
$calendar = new Calendar();
$objectid = $model->id;
$catroom = Catroom::find()->where(['object_id'=>$objectid])->all();
$object = Object::findOne(['id'=>$objectid]);

// $d=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
$d="31";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue skin-cabinet sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= $this->render('header.php',['directoryAsset' => $directoryAsset]) ?>
     <?= $this->render('_left.php',['object' => $model]) ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="calendar-index">
                <div class="alerts-div">
                    <div class="alert alert-danger alert-dismissable" id="danger-message" style="display: none;"></div>
                    <!-- <div class="alert-success alert fade in" id="success-message" style="display: none;"></div> -->
                </div>
                <?php if (count($catroom)==0): ?>
                    <h2 class="text-center"><?= Html::a(Yii::t('app', 'Добавьте категории номеров'), ['update/catroom/'.$model->id]) ?> , для редактирования календаря.</h2>
                <?php else: ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h1 class="text-center"><?=$this->title?></h1>
                            <?php foreach ($catroom as $cat): ?>
                                <?php $catid = $cat->id; $objectid = $cat->object_id;?>

                                <script type="text/javascript">
                                function functionsss(year, month, day, catid) {
                                    var fd = year+'-'+month+'-'+day;
                                    $.post('../../calendar/isengaged?catroomid='+catid+'&datefrom='+fd, function(data){
                                    $('#datediv-'+catid+' .datepicker--cell').each(function(){
                                            if(data == true){
                                                // if($(this).attr('data-date')==day && $(this).attr('data-month')==month && $(this).attr('data-year')==year){
                                                if ($(this).attr('data-date') == day) {
                                                    $(this).addClass('engaged');
                                                }
                                            } else if(data == false) {
                                                    if ($(this).attr('data-date') == day) {
                                                        $(this).removeClass('engaged');
                                                    }
                                                }
                                       });
                                   });
                                }
                                function changeMonthMy(y,m,cat) {

                                    var datadate = $('#datediv-'+cat+' .datepicker--cells.datepicker--cells-days div:nth-child(1)').attr('data-year')+'-'
                                    +(parseInt($('#datediv-'+cat+' .datepicker--cells.datepicker--cells-days div:nth-child(1)').attr('data-month'))+1)+'-'
                                    +$('#datediv-'+cat+' .datepicker--cells.datepicker--cells-days div:nth-child(1)').attr('data-date');
                                    // var d =new Date(datadate).getUTCDate();
                                    // var divs = $('.column-scrol-count-'+cat+' .column-item').length;
                                    // var lastchildroomh = $('.column-scrol-count-'+cat+' .column-item:last-child form #room-hidden').val();
                                    // var lastchildroommax = $('.column-scrol-count-'+cat+' .column-item:last-child form #room-hidden').attr('max');
                                    //
                                    // if (d-divs==1) {
                                    //     $('.column-scrol-count-'+cat).append('<div class="column-item count-'+cat+'-31" data-date="'+y+'-'+m+'-31"><form><input type="hidden" value="'+lastchildroomh+'" id="room-hidden"><input type="hidden" name="Freeroom[object_id]" value="<?=$objectid?>"><input type="hidden" name="Freeroom[catroom_id]" value="'+cat+'" class="catroom-free"><input type="hidden" name="Freeroom[check_date]" id="count-'+cat+'-31" class="check_date_catroom" value="'+y+'-'+m+'-31"><div class="form-group field-freeroom-room_count required"><input type="number" id="freeroom-room_count" class="no-border readonlycal" name="Freeroom[room_count]" value="'+lastchildroomh+'" max="'+lastchildroommax+'" onchange="formsubmitajax($(this))" min="0" aria-required="true"></div></form></div>');
                                    // } else if (d-divs==-1){
                                    //     $('.column-scrol-count-'+cat+' .column-item:last-child').remove();
                                    // }
                                    // alert(d-divs);
                                    datadate = formatDate(datadate);
                                    if (new Date(datadate).getMonth() > new Date().getMonth()) {
                                        $('.count-'+cat+' #freeroom-room_count').attr('readonly', false).removeClass('readonlycal');
                                        $('.price-'+cat+' #price-price').attr('readonly', false).removeClass('readonlycal');
                                        $('.add-'+cat+' #addprice-price').attr('readonly', false).removeClass('readonlycal');
                                    }
                                    $.post('../../calendar/getyandmfreerooms?y='+y+'&m='+m+'&catid='+cat, function (data) {
                                        var datajson = JSON.parse(data);
                                        datajson.forEach(function(entry) {
                                            var datadate = $('#datediv-'+entry.cat+' .datepicker--cells.datepicker--cells-days div:nth-child('+entry.i+')').attr('data-year')+'-'
                                            +(parseInt($('#datediv-'+entry.cat+' .datepicker--cells.datepicker--cells-days div:nth-child('+entry.i+')').attr('data-month'))+1)+'-'
                                            +$('#datediv-'+entry.cat+' .datepicker--cells.datepicker--cells-days div:nth-child('+entry.i+')').attr('data-date');
                                            datadate = formatDate(datadate);
                                            $.post('../../calendar/getcolor?catroomid='+cat+'&datefrom='+datadate, function(data){
                                                    if(data == true){
                                                    $('#datediv-'+entry.cat+' .datepicker--cells.datepicker--cells-days div:nth-child('+entry.i+')').addClass('engaged');
                                                } else if(data == false) {
                                                    $('#datediv-'+entry.cat+' .datepicker--cells.datepicker--cells-days div:nth-child('+entry.i+')').removeClass('engaged');
                                                    }
                                            });
                                            if(datadate < formatDate(new Date())){
                                                // alert(entry.cat);
                                                $('.count-'+entry.cat+'-'+entry.i+' #freeroom-room_count').attr('readonly', true).addClass('readonlycal').val(entry.free_value);
                                                $('.price-'+entry.cat+'-'+entry.i+' #price-price').attr('readonly', true).addClass('readonlycal').val(entry.price_value);
                                                $('.add-'+entry.cat+'-'+entry.i+' #addprice-price').attr('readonly', true).addClass('readonlycal').val(entry.addprice_value);
                                            } else {
                                                $('.count-'+entry.cat+'-'+entry.i+' #freeroom-room_count').attr('readonly', false).removeClass('readonlycal').val(entry.free_value);
                                                $('.price-'+entry.cat+'-'+entry.i+' #price-price').attr('readonly', false).removeClass('readonlycal').val(entry.price_value);
                                                $('.add-'+entry.cat+'-'+entry.i+' #addprice-price').attr('readonly', false).removeClass('readonlycal').val(entry.addprice_value);
                                            }
                                            $('.count-'+entry.cat+'-'+entry.i).attr('data-date',datadate);
                                            $('.price-'+entry.cat+'-'+entry.i).attr('data-date',datadate);
                                            $('.add-'+entry.cat+'-'+entry.i).attr('data-date',datadate);
                                            $('#count-'+entry.cat+'-'+entry.i).val(datadate);
                                            $('#price-'+entry.cat+'-'+entry.i).val(datadate);
                                            $('#add-'+entry.cat+'-'+entry.i).val(datadate);
                                            $('.add-'+entry.i).attr('data-date',datadate);
                                            $('.discount-'+entry.i).attr('data-date',datadate);
                                            // alert(datadate);
                                            // alert(formatDate(new Date()));
                                        });
                                    });
                                }
                                </script>
                                <div class="cat-item">
                                    <div class="col-sm-12 calendar-room" id="calendar-form-<?=$cat->id?>">
                                        <h3><?=$cat->title?><i class="pull-right fa fa-pencil-square-o" aria-hidden="true"></i></h3>
                                        <div class="calendar-form-<?=$cat->id?>" style="display:none">
                                            <div class="room-item">
                                                <div class="overlay"></div>
                                                <div class="row">
                                                    <div class="col-sm-2 labels-group">
                                                        <label class="labelfirst" data-toggle="tooltip" title="Вы можете закрыть/открыть дату для бронирования нажав на нее. Закрытые даты выделены красным."><label class="calendar-label"><div class="free"></div>Открыто/</label><label class="calendar-label"><div class="engaged"></div>Закрыто</label></label>
                                                    </div>
                                                    <div class="col-sm-10" id="datediv-<?=$cat->id?>">
                                                        <input type="hidden" id="datepicker-<?=$cat->id?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <p class="btn-class">Количество свободных номеров</p>
                                                        <?php Modal::begin([
                                                            'header' => '<h3 class="modal-title text-center">Введите количество свободных номеров на выбранные даты</h3>',
                                                            'toggleButton' => ['label' => 'Изменить период', 'class' => 'btn btn-modal-open btn-default'],
                                                        ]);?>
                                                            <div id="count-add-<?=$cat->id?>">
                                                                <?php $form = ActiveForm::begin([
                                                                    'enableAjaxValidation' => true,
                                                                    'enableClientValidation' => true,
                                                                    'fieldConfig' => ['template' => "{label}\n{input}\n{hint}\n",],
                                                                    'action'=>['/calendar/roomsperiod/'],
                                                                ]); ?>
                                                                    <?= $form->field($freeroom, 'catroom_id')->hiddenInput(["value"=>$cat->id])->label(false) ?>
                                                                    <?= $form->field($freeroom, 'object_id')->hiddenInput(["value"=>$objectid])->label(false)?>
                                                                    <h4 class="text-center">Укажите период</h4>
                                                                    <div class="row">
                                                                        <div class="col-sm-4 pad text-center">
                                                                            <?= $form->field($freeroom, 'from')->textInput(['class'=>'form-control price-from', 'readonly' => true, 'placeholder'=>'Начало'])->label(false) ?>
                                                                            <i class='fa fa-calendar text-primary dateicon' aria-hidden='true'></i>
                                                                            <input type="text" readonly="true" class="daterange1" value=""/>
                                                                        </div>
                                                                        <div class="col-sm-4 pad text-center">
                                                                            <?= $form->field($freeroom, 'to')->textInput(['class'=>'form-control price-to', 'readonly' => true, 'placeholder'=>'Окончание'])->label(false) ?>
                                                                            <i class='fa fa-calendar text-primary dateicon' aria-hidden='true'></i>
                                                                        </div>
                                                                        <div class="col-sm-4 pad text-center">
                                                                            <?= $form->field($freeroom, 'room_count')->input('number',['class'=>'form-control','id'=>'freeroom-'.$cat->id, 'placeholder'=>'Количество', 'max'=>$cat->count_rooms, 'min'=>0])->label(false) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group text-center">
                                                                            <?= Html::Button(Yii::t('app', 'Сохранить'), ['class' => 'applay pb btn', 'onclick'=>'formsubmit($("#freeroom-'.$cat->id.'"))']) ?>
                                                                        </div>
                                                                    </div>
                                                                <?php ActiveForm::end(); ?>
                                                            </div>
                                                        <?php Modal::end();?>
                                                    </div>
                                                    <div class="col-sm-10 scrollable">
                                                        <div class="tab-column">
                                                            <div class="column">
                                                                <div class="column-body">
                                                                    <div class="column-scrol column-scrol-count-<?=$cat->id?>">
                                                                        <?php for ($i=1; $i <= $d; $i++): ?>
                                                                            <div class="column-item count-<?=$cat->id?>-<?=$i?>">
                                                                                <form>
                                                                                    <input type="hidden" value="<?=$cat->count_rooms?>" id="room-hidden">
                                                                                    <input type="hidden" name="Freeroom[object_id]" value="<?=$objectid?>">
                                                                                    <input type="hidden" name="Freeroom[catroom_id]" value="<?=$cat->id?>" class="catroom-free">
                                                                                    <input type="hidden" name="Freeroom[check_date]" id="count-<?=$cat->id?>-<?=$i?>" class="check_date_catroom">
                                                                                    <?php if ($free_value=Freeroom::findOne(['check_date'=>date(date("Y")."-".date("m")."-".$i), 'catroom_id'=>$cat->id])): ?>
                                                                                        <?= $form->field($freeroom, 'room_count')->input('number', ['value'=>$free_value->room_count, 'max'=>$cat->count_rooms,'onchange'=>'formsubmitajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                    <?php else: ?>
                                                                                        <?= $form->field($freeroom, 'room_count')->input('number', ['value'=>$cat->count_rooms, 'max'=>$cat->count_rooms,'onchange'=>'formsubmitajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                    <?php endif; ?>
                                                                                </form>
                                                                            </div>
                                                                        <?php endfor;?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <p class="btn-class">Основная цена за <?=$cat->adult_count?> взр. и <?=$cat->child_count?> детей бесплатно</p>
                                                        <?php Modal::begin([
                                                            'header' => '<h3 class="modal-title text-center">Добавить ценовой период</h3>',
                                                            'toggleButton' => ['label' => 'Изменить период', 'class' => 'btn btn-modal-open btn-default'],
                                                        ]);?>
                                                        <div id="price-add">
                                                            <?php $form = ActiveForm::begin([
                                                                'enableClientValidation' => true,
                                                                'action'=>['/price/priceperiod'],
                                                            ]); ?>
                                                                <?= $form->field($price, 'catroom_id')->hiddenInput(["value"=>$cat->id])->label(false) ?>
                                                                <?= $form->field($price, 'object_id')->hiddenInput(["value"=>$objectid])->label(false)?>
                                                                <h4 class="text-center">Укажите период</h4>
                                                                <div class="row">
                                                                    <div class="col-sm-6 pad text-center">
                                                                        <?= $form->field($price, 'from')->textInput(['class'=>'form-control price-from', 'readonly' => true, 'placeholder'=>'Начало'])->label(false) ?>
                                                                        <i class='fa fa-calendar text-primary dateicon' aria-hidden='true'></i>
                                                                        <input type="text" readonly="true" class="daterange1" value=""/>
                                                                    </div>
                                                                    <div class="col-sm-6 pad text-center">
                                                                        <?= $form->field($price, 'to')->textInput(['class'=>'form-control price-to', 'readonly' => true, 'placeholder'=>'Окончание'])->label(false) ?>
                                                                        <i class='fa fa-calendar text-primary dateicon' aria-hidden='true'></i>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?= $form->field($price, 'work_day')->input('number', ['class'=>'priceinput', 'min'=>0]) ?>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?= $form->field($price, 'weekend')->input('number', ['class'=>'weekpriceinput', 'min'=>0])?>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group text-center">
                                                                        <p class="text-danger pop-error"></p>
                                                                        <?= Html::Button(Yii::t('app', 'Создать ценовой период на указанные даты'), ['class' => 'applay pb btn', 'onclick'=>'submitprice($(this))']) ?>
                                                                    </div>
                                                                </div>
                                                            <?php ActiveForm::end(); ?>
                                                        </div>
                                                        <?php Modal::end();?>
                                                    </div>
                                                    <div class="col-sm-10 scrollable">
                                                        <div class="tab-column">
                                                            <div class="column">
                                                                <div class="column-body">
                                                                    <div class="column-scrol column-scrol-price">
                                                                        <?php for ($i=1; $i <= $d; $i++):?>
                                                                            <div class="column-item price-<?=$cat->id?>-<?=$i?>">
                                                                                <form>
                                                                                    <input type="hidden" name="Price[object_id]" value="<?=$objectid?>">
                                                                                    <input type="hidden" name="Price[catroom_id]" value="<?=$cat->id?>" class="catroom-price">
                                                                                    <input type="hidden" name="Price[check_date]" id="price-<?=$cat->id?>-<?=$i?>" class="date_price">
                                                                                    <?php
                                                                                        $checkweek = date('w', strtotime(date("Y")."-".date("m")."-".$i));
                                                                                        if (Weekdays::findOne(['object_id'=>$cat->object_id])) {
                                                                                            $weekdays = Weekdays::findOne(['object_id'=>$cat->object_id])->week_days;
                                                                                        } else {
                                                                                            $weekdays = "";
                                                                                        }
                                                                                     ?>
                                                                                    <?php if ($price_value=Price::findOne(['check_date'=>date("Y")."-".date("m")."-".$i, 'catroom_id'=>$cat->id])): ?>
                                                                                        <?php if (preg_match('/'.$checkweek.'/',$weekdays)): ?>
                                                                                            <?= $form->field($price, 'price')->input('number', ['value'=>$price_value->weekend,'onchange'=>'submitpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                        <?php else: ?>
                                                                                            <?= $form->field($price, 'price')->input('number', ['value'=>$price_value->work_day,'onchange'=>'submitpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                        <?php endif; ?>
                                                                                    <?php else: ?>
                                                                                        <?php if (preg_match('/'.$checkweek.'/',$weekdays)): ?>
                                                                                            <?= $form->field($price, 'price')->input('number', ['value'=>$cat->weekend,'onchange'=>'submitpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                        <?php else: ?>
                                                                                            <?= $form->field($price, 'price')->input('number', ['value'=>$cat->work_day,'onchange'=>'submitpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                        <?php endif; ?>
                                                                                    <?php endif; ?>
                                                                                </form>
                                                                            </div>
                                                                        <?php endfor; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ($cat->add_count!=0): ?>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <p class="btn-class">Цена за 1 доп. место</p>
                                                            <?php Modal::begin([
                                                                'header' => '<h3 class="modal-title text-center">Добавить ценовой период</h3>',
                                                                'toggleButton' => ['label' => 'Изменить период', 'class' => 'btn btn-modal-open btn-default'],
                                                            ]);?>
                                                            <div id="price-add">
                                                                <?php $form = ActiveForm::begin([
                                                                    'enableClientValidation' => true,
                                                                    'action'=>['/price/addpriceperiod'],
                                                                ]); ?>
                                                                    <?= $form->field($addprice, 'catroom_id')->hiddenInput(["value"=>$cat->id])->label(false) ?>
                                                                    <?= $form->field($addprice, 'object_id')->hiddenInput(["value"=>$objectid])->label(false)?>
                                                                    <h4 class="text-center">Укажите период</h4>
                                                                    <div class="row">
                                                                        <div class="col-sm-6 pad text-center">
                                                                            <?= $form->field($addprice, 'from')->textInput(['class'=>'form-control price-from', 'readonly' => true, 'placeholder'=>'Начало'])->label(false) ?>
                                                                            <i class='fa fa-calendar text-primary dateicon' aria-hidden='true'></i>
                                                                            <input type="text" readonly="true" class="daterange1" value=""/>
                                                                        </div>
                                                                        <div class="col-sm-6 pad text-center">
                                                                            <?= $form->field($addprice, 'to')->textInput(['class'=>'form-control price-to', 'readonly' => true, 'placeholder'=>'Окончание'])->label(false) ?>
                                                                            <i class='fa fa-calendar text-primary dateicon' aria-hidden='true'></i>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <?= $form->field($addprice, 'work_day')->input('number', ['class'=>'priceinput', 'min'=>0]) ?>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <?= $form->field($addprice, 'weekend')->input('number', ['class'=>'weekpriceinput', 'min'=>0])?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group text-center">
                                                                            <p class="text-danger pop-error"></p>
                                                                            <?= Html::Button(Yii::t('app', 'Создать ценовой период на указанные даты'), ['class' => 'applay pb btn', 'onclick'=>'submitprice($(this))']) ?>
                                                                        </div>
                                                                    </div>
                                                                <?php ActiveForm::end(); ?>
                                                            </div>
                                                            <?php Modal::end();?>
                                                        </div>
                                                        <div class="col-sm-10 scrollable">
                                                            <div class="tab-column">
                                                                <div class="column">
                                                                    <div class="column-body">
                                                                        <div class="column-scrol column-scrol-add">
                                                                            <?php for ($i=1; $i <= $d; $i++):?>
                                                                                <div class="column-item add-<?=$cat->id?>-<?=$i?>">
                                                                                    <form>
                                                                                        <input type="hidden" name="Addprice[object_id]" value="<?=$objectid?>">
                                                                                        <input type="hidden" name="Addprice[catroom_id]" value="<?=$cat->id?>" class="catroom-addprice">
                                                                                        <input type="hidden" name="Addprice[check_date]" id="add-<?=$cat->id?>-<?=$i?>" class="date_addprice">
                                                                                        <?php
                                                                                            $checkweek = date('w', strtotime(date("Y")."-".date("m")."-".$i));
                                                                                            if (Weekdays::findOne(['object_id'=>$cat->object_id])) {
                                                                                            $weekdays = Weekdays::findOne(['object_id'=>$cat->object_id])->week_days;
                                                                                            } else {
                                                                                                $weekdays = "";
                                                                                            }
                                                                                         ?>
                                                                                        <?php if ($add_price_value=Addprice::findOne(['check_date'=>date("Y")."-".date("m")."-".$i, 'catroom_id'=>$cat->id])): ?>
                                                                                            <?php if (preg_match('/'.$checkweek.'/',$weekdays)): ?>
                                                                                                <?= $form->field($addprice, 'price')->input('number', ['value'=>$add_price_value->weekend,'onchange'=>'addpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                            <?php else: ?>
                                                                                                <?= $form->field($addprice, 'price')->input('number', ['value'=>$add_price_value->work_day,'onchange'=>'addpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                            <?php endif; ?>
                                                                                        <?php else: ?>
                                                                                            <?php if (preg_match('/'.$checkweek.'/',$weekdays)): ?>
                                                                                                <?= $form->field($addprice, 'price')->input('number', ['value'=>$cat->weekend_add,'onchange'=>'addpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                            <?php else: ?>
                                                                                                <?= $form->field($addprice, 'price')->input('number', ['value'=>$cat->work_add,'onchange'=>'addpriceajax($(this))', 'class'=>'no-border', 'min'=>0])->label(false) ?>
                                                                                            <?php endif; ?>
                                                                                        <?php endif; ?>
                                                                                    </form>
                                                                                </div>
                                                                            <?php endfor; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <hr>
                                                <div class="cat-price">
                                                    <div class="row">
                                                        <div class="col-sm-12 cat-name">
                                                            <h5 class="pull-left" data-toggle="tooltip" title="Если при размещении ребенка на доп. месте предоставляется скидка, то укажите">Скидки на детей на доп.место</h5>
                                                            <div class="plus pull-right">
                                                                <?php Modal::begin([
                                                                        'header' => '<label class="modal-title text-center">Скидка за 1 ребенка/сутки на 1 доп.место.</label>',
                                                                        'toggleButton' => ['label' => 'Указать скидки', 'class' => 'btn btn-default btn-modal-open applay'],
                                                                    ]);?>
                                                                        <div id="percent-add">
                                                                            <h5 class="text-center"> Укажите возрастные интервалы (в годах) и размер скидок для них. (Например от 2 до 5 лет - 50. Это значит что скидка 50% будет идти начиная с 2 лет (включительно) и до 5 лет(включительно). Следующий интервал надо начинать с 6 лет.)</h5>
                                                                            <?php $discount = Discount::find()->where(['catroom_id'=>$cat->id])->all() ?>
                                                                            <?php $form = ActiveForm::begin([
                                                                                'enableClientValidation' => true,
                                                                                'action'=>['/discount/create'],
                                                                            ]); ?>
                                                                            <?= $form->field($discountnew, 'object_id')->hiddenInput(["value"=>$objectid])->label(false)?>
                                                                            <?= $form->field($discountnew, 'catroom_id')->hiddenInput(['value'=>$cat->id])->label(false) ?>
                                                                            <div class="discount discnow">
                                                                                <?php $count = 1 ?>

                                                                                    <?php foreach ($discount as $disc): ?>
                                                                                        <div class="row count">
                                                                                            <div class="col-sm-3">
                                                                                                <?= $form->field($disc, 'fromage'.$count)->input('number', ["value"=>$disc->fromage, 'max'=>17, 'min'=>0, 'maxlength' => true])->label(false) ?>
                                                                                            </div>
                                                                                            <div class="col-sm-3">
                                                                                                <?= $form->field($disc, 'age'.$count)->input('number', ["value"=>$disc->age], ['max'=>17, 'min'=>0], ['maxlength' => true])->label(false) ?>
                                                                                            </div>
                                                                                            <div class="col-sm-4">
                                                                                                <?= $form->field($disc, 'percent'.$count)->input('number', ["value"=>$disc->percent], ['max'=>100], ['maxlength' => true])->label(false)?>
                                                                                            </div>
                                                                                            <?= Html::a('<i class="fa fa-trash text-danger" aria-hidden="true"></i>', ['/discount/delete/'.$disc->id], ['class'=>'btn-remove', 'data' => ['method' => 'post']]) ?>
                                                                                        </div>
                                                                                        <?php $count++ ?>
                                                                                    <?php endforeach; ?>
                                                                                    <?php $discount1 = Discount::find()->all();
                                                                                    $dcount=1;
                                                                                    foreach ($discount1 as $d1) {
                                                                                        if ($d1->catroom_id === $cat->id) {
                                                                                            $dcount++;
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <div class="row count" id="<?=$dcount?>">
                                                                                        <div class="col-sm-3">
                                                                                            <?= $form->field($discountnew, 'fromage'.$dcount)->input('number', ['placeholder' => 'От (вкл)', 'max'=>16, 'min'=>0], ['maxlength' => true])->label(false) ?>
                                                                                        </div>
                                                                                        <div class="col-sm-3">
                                                                                            <?= $form->field($discountnew, 'age'.$dcount)->input('number', ['placeholder' => 'До (вкл)', 'max'=>17, 'min'=>0], ['maxlength' => true])->label(false) ?>
                                                                                        </div>
                                                                                        <div class="col-sm-4">
                                                                                            <?= $form->field($discountnew, 'percent'.$dcount)->input('number', ['placeholder' => 'Скидка, %', 'max'=>100], ['maxlength' => true])->label(false)?>
                                                                                        </div>
                                                                                    </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-sm-5">
                                                                                    <?= Html::submitButton('<i class="fa fa-check"></i> Сохранить', ['class' => 'btn btn-common']) ?>
                                                                                </div>
                                                                                <div class="col-sm-5">
                                                                                    <button type="button" name="button" id="addform" class="btn btn-common addform"><i class="fa fa-plus"></i>Добавить интервал</button>
                                                                                </div>
                                                                            </div>
                                                                            <?php ActiveForm::end(); ?>
                                                                        </div>
                                                                    <?php
                                                                Modal::end();?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 white">
                                                            <?php foreach ($discount as $disc): ?>
                                                                <h5 class="col-sm-12 disc-perc">Для детей от <?=$disc->fromage?> до <?=$disc->age?> лет скидка на доп. места <?=$disc->percent?>%.</h5>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $closeds = Calendar::find()->where(['catroom_id'=>$cat->id])->andWhere(['status'=>1])->orderBy('check_date')->all();
                                            $closedates = [];
                                            foreach ($closeds as $closed) {
                                                $closedates[] =[
                                                    'fromdate' => $closed->check_date,
                                                ];
                                            }
                                            $jsondate = json_encode($closedates);
                                            $base = Yii::$app->request->baseUrl;
                                            $catid = $cat->id;
                                            ?>
                                            <?php
                                             $js = "
                                            var datajson$cat->id = $jsondate;
                                            var closeds$cat->id = [], closedfroms$cat->id = [], closedtos$cat->id = [];
                                            for (i = 0; i < datajson$cat->id.length; i++) {
                                                closeds$cat->id.push(datajson$cat->id[i].fromdate);
                                                closedfroms$cat->id.push(datajson$cat->id[i].fromdate);
                                            }
                                            $('#datepicker-".$cat->id."').datepicker({
                                                nextHtml: '<i class=fa data-cat=$cat->id>&#xf105;</i>',
                                                prevHtml: '<i class=fa data-cat=$cat->id>&#xf104;</i>',
                                                inline:true,
                                                multidate: true,
                                                multipleDates: true,
                                                minDate:new Date(),
                                                dateFormat:'yyyy-mm-dd',
                                                onRenderCell: function(date, cellType) {
                                                    for (i = 0; i < datajson$cat->id.length; i++) {
                                                        if (cellType == 'day' && Date.parse(formatDate(date)) == Date.parse(datajson$cat->id[i].fromdate)) {
                                                            return {
                                                                classes: 'engaged',
                                                            }
                                                        }
                                                    }
                                               },
                                               onSelect: function(fd, date, picker) {
                                                   if (fd) {
                                                       alert(fd);
                                                       var a = fd.split(','),
                                                        i;
                                                        var k=a.length;
                                                        alert(a[k-1]);
                                                        fd= a[k-1];
                                                        $.post('$base/calendar/isengaged?catroomid=$catid&datefrom='+fd, function(data){
                                                            fd = new Date(fd);
                                                        $('#datediv-$catid .datepicker--cell').each(function(){
                                                            if($(this).attr('data-date')==fd.getDate() && $(this).attr('data-month')==fd.getMonth() && $(this).attr('data-year')==fd.getFullYear()){
                                                                if(data == true){
                                                                  $(this).addClass('red');
                                                              } else {
                                                                  $(this).removeClass('red');
                                                              }
                                                            }
                                                           });
                                                       });
                                                    }
                                                }
                                            });

                                            ";
                                            $this->registerJs($js);?>
                                        </div>
                                    </div>
                                </div>
                                <?php  $catid = $cat->id;
                                 $jsdate = "for (var j = 1; j <= $d; j++) {
                                    var datadate = $('.datepicker--cells.datepicker--cells-days div:nth-child('+j+')').attr('data-year')+'-'
                                    +(parseInt($('.datepicker--cells.datepicker--cells-days div:nth-child('+j+')').attr('data-month'))+1)+'-'
                                    +$('.datepicker--cells.datepicker--cells-days div:nth-child('+j+')').attr('data-date');
                                    datadate = formatDate(datadate);
                                    $('.count-$catid-'+j).attr('data-date',datadate);
                                    $('.price-$catid-'+j).attr('data-date',datadate);
                                    $('.add-$catid-'+j).attr('data-date',datadate);
                                    $('#count-$catid-'+j).val(datadate);
                                    $('#price-$catid-'+j).val(datadate);
                                    $('#add-$catid-'+j).val(datadate);
                                    $('.add-'+j).attr('data-date',datadate);
                                    $('.discount-'+j).attr('data-date',datadate);
                                    if(new Date(datadate) < new Date(formatDate(new Date()))){
                                        $('.count-$catid-'+j+' #freeroom-room_count').attr('readonly', true).addClass('readonlycal');
                                        $('.price-$catid-'+j+' #price-price').attr('readonly', true).addClass('readonlycal');
                                        $('.add-$catid-'+j+' #addprice-price').attr('readonly', true).addClass('readonlycal');
                                    }
                                }
                            //     $('.datepicker--cell').each(function(){
                            //         $(this).on('click',function(){
                            //             alert('1');
                            //         });
                            //    });
                                ";
                                $this->registerJs($jsdate); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <p class="text-center">TvoyRay.ru 2007-2012. <?=Yii::$app->name?> все права защищены &copy; 2012-<?=date('Y')?></p>
    </footer>
</div>
<?php if (Yii::$app->session->has('roomid')): ?>
    <?php $jscat = '$(".calendar-form-'.Yii::$app->session->get("roomid").'").show()'; $this->registerJs($jscat); Yii::$app->session->remove('roomid');?>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('catroomid')):
$catid = Yii::$app->session->getFlash('catroomid');
$js = "$('.calendar-form-$catid').show();
$('html, body').animate({ scrollTop: $('.calendar-form-$catid').offset().top }, 10);";
$this->registerJs($js);
endif; ?>
<script>
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (day.length < 2) day = '0' + day;
    if (month.length < 2) month = '0' + month;
    return [year, month, day].join('-');
}
function formsubmit(el) {
    if (el.val() <= el.attr('max')) {
        el.closest('form').submit();
        if ($(".danger-message").html()) {
            $(".danger-message").remove();
        }
    } else {
        el.val(el.attr('max'));
        if ($(".danger-message").html()) {
            $(".danger-message").remove();
        }
    }
}
function submitpriceajax(el) {
    if ($(".danger-message").html()) {
        $(".danger-message").remove();
    }
    var catid = el.closest('form').find('.catroom-price').val();
    var check_date = el.closest('form').find('.date_price').val();
    $.post("../../price/price?objectid=<?=$objectid?>&catid="+catid+"&check_date="+check_date+"&price="+el.val(), function(data) {
        if (data == true) {
            if (!$(".alerts-div .success2").html()) {
                $(".success1").remove();
                $( ".alerts-div" ).append( '<div class="alert-success success2 alert fade in" id="success-message" style="display: block;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>Данные успешно сохранены.</div>' );
            }
        }
    });
}
function addpriceajax(el) {
    if ($(".danger-message").html()) {
        $(".danger-message").remove();
    }
    var catid = el.closest('form').find('.catroom-addprice').val();
    var check_date = el.closest('form').find('.date_addprice').val();
    $.post("../../price/addprice?objectid=<?=$objectid?>&catid="+catid+"&check_date="+check_date+"&price="+el.val(), function(data) {
        if (data == true) {
            if (!$(".alerts-div .success2").html()) {
                $(".success1").remove();
                $( ".alerts-div" ).append( '<div class="alert-success success2 alert fade in" id="success-message" style="display: block;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>Данные успешно сохранены.</div>' );
            }
        }
    });
}
function formsubmitajax(el) {
    if (parseInt(el.val()) <= parseInt(el.attr('max'))) {
        var catid = el.closest('form').find('.catroom-free').val();
        var check_date = el.closest('form').find('.check_date_catroom').val();
        $.post("../../calendar/freeroom?objectid=<?=$objectid?>&catid="+catid+"&check_date="+check_date+"&roomcount="+el.val(), function(data) {
            if (data == true) {
                if (!$(".alerts-div .success1").html()) {
                    $(".success2").remove();
                    $(".alert-danger").remove();
                }
            }
        });

    } else {
        el.val(el.attr('max'));
        if (!$(".alerts-div .alert-danger").html()) {
            $(".alert-success").remove();
            $( ".alerts-div" ).append( '<div class="alert alert-danger alert-dismissable" id="danger-message" style="display: block;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i>Значение должно быть меньше или равно '+el.attr('max')+'. Вы можете добавить количество номеров к разделе «Категории номеров»</div>' );
        }
        $.post("../../calendar/freeroom?objectid=<?=$objectid?>&catid="+catid+"&check_date="+check_date+"&roomcount="+el.attr('max'));
    }
}
function submitprice(el){
    if ((el.closest('form').find('.priceinput').val()!="" || el.closest('form').find('.weekpriceinput').val()!="")
         && el.closest('form').find('.price-from').val()!="" && el.closest('form').find('.price-to').val()!="") {
        el.closest('form').submit();
    } else {
        el.closest('form').find('.pop-error').html('Заполните один из полей цен и период.');
    }
}

</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
