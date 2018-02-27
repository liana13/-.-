<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Image;
use app\models\Person;
use app\models\Review;
use app\models\User;
use app\models\Properties;
use mirocow\yandexmaps\Canvas as YandexCanvas;
use mirocow\yandexmaps\Map as YandexMap;
use mirocow\yandexmaps\objects\Placemark as Placemark;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\Field;
use app\models\Tarif;
use app\models\Servis;
use app\models\Config;

$address = Config::findOne(["id"=>1])->address.", ".Config::findOne(["id"=>1])->title;
$cat_config = Config::findOne(["id"=>1])->objectcat_id;
$config = $address.", ";
$street = explode($config, $model->address)[1];
$fields = Field::find()->where(['!=', 'class', ""])->andWhere(['tarif_id'=>Tarif::findOne(['tarifid'=>$model->tarif_id])->id])->orWhere(['tarif_id'=>NULL])->andWhere(['!=', 'class', ""])->andWhere(['id'=>1])->orWhere(['id'=>2])->orWhere(['id'=>32])->orWhere(['id'=>34])->orderBy('sort asc')->all();
$this->title = $model->title;
$reviews = Review::find()->where(['object_id'=>$model->id, 'status'=>1])->all();
if (Person::findOne(['id'=>$model->user_id])) {
    $phone=Person::findOne(['id'=>$model->user_id])->phone;
}
$image = new Image();
$images=Image::find()->where(['object_id'=>$model->id])->orderBy('id')->all();
$tarif = $model->new_tarif;
$tarif_images = Tarif::findOne(['tarifid'=>$tarif]);
if (count($images) == $tarif_images->photo) {
    $allow = 1;
} else {
    $allow = 0;
}
Modal::begin([
    'id'=>'address-modal',
    'size'=>'modal-sm',
    'header' => '<h3 class="modal-title text-center">Изменить адрес</h3>',
]);?>
<script type="text/javascript">
    function setaddress(){
        var address = document.getElementById('address-value').value;
        var config = document.getElementById('address-config').value;
        document.getElementById('address').value = config+", "+address;;
    }
</script>
<div id="address-content">
    <?php $form = ActiveForm::begin([
        'id'=>'addressform',
    ]); ?>

            <?= $form->field($model, 'address_config')->textarea(['id'=>'address-config', 'value'=>$address, 'disabled'=>true])->label(false) ?>
            <?= $form->field($model, 'address')->textarea(['id'=>'address-value', 'value'=>$street])->label(false) ?>        <div class="form-group text-center">
            <?= Html::button('Сохранить', ['data-dismiss'=>'modal', 'class' => 'btn btn-common btn-save-update', 'onclick' => 'setaddress()']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
Modal::begin([
    'id'=>'delete-modal',
    'size'=>'modal-sm',
    'header' => '<h3 class="modal-title text-center">Уведомление</h3>',
]);?>
<div id="address-content">
    <?php $form = ActiveForm::begin([
        'id'=>'deleteform',
        'action'=>['/update/deleteimage/'.$model->id],
    ]); ?>
        <?= $form->field($model, 'img')->hiddenInput(['id'=>'hiddenimg'])->label(false) ?>
        <h3 class="text-center">Вы уверены,что хотите удалить?</h3>
        <div class="form-group text-center">
            <?= Html::submitButton('ДА', ['class' => 'btn btn-common', 'name' => 'delete-button']) ?>
            <?= Html::button('НЕТ', ['class' => 'btn btn-common', 'onclick' => 'deletebutton()']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Modal::end();
Modal::begin([
    'id'=>'addimages-modal',
    'size'=>'custom-modal',
    'header' => '<h3 class="modal-title text-center">Добавить изображение</h3>',
]);?>
<div id="addimages-content">
    <?php $form = ActiveForm::begin([
        'id'=>'addimageform',
        'action'=>['/update/addimage/'.$model->id],
    ]); ?>
    <div class="fileimg">
        <span class="fileInputobj"><i class="fa fa-download" aria-hidden="true"></i> Добавить изображение</span>
        <?=$form->field($image, 'file')->fileInput(['id' => 'files'])->label(false)?>
    </div>
    <div id="list"></div>
    <div class="row">
        <div class="col-sm-12">
            <?=$form->field($image, 'value')->textInput(['id' => 'text_value_image', 'placeholder'=>'Описание фото'])->label(false)?>
            <?=$form->field($image, 'main')->hiddenInput(['value' => '1'])->label(false)?>
        </div>
    </div>
    <div class="form-group text-center">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-common']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Modal::end();?>
<?php $form = ActiveForm::begin(['options'=>['id'=>'update-form']]); ?>
    <div class="object-view object-update">
        <div class="row">
            <div class="col-lg-7">
                <div id="slider" class="flexslider <?=(count($images)==1 || count($images)==0) ? 'single' : ''?>">
                    <ul class="slides">
                        <?php if (Image::find()->where(['object_id'=>$model->id])): ?>
                            <?php if (count($images) != 0): ?>
                                <?php foreach ($images as $img): ?>
                                    <li><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/'. $img->image), ['alt' => Html::encode($model->title), 'class'=>"clasadimage"]); ?><i class="fa fa-trash" aria-hidden="true" onclick="alertfunction(<?=$img->id?>)" title="Удалить"></i></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><img src="<?=Yii::$app->request->baseUrl?>/upload/images/default/default.jpg" alt="<?=Html::encode($model->title)?>"/></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <div id="carousel" class="flexslider">
                    <ul class="slides">
                        <?php if (count($images) != 0): ?>
                            <?php if ($allow == 0): ?>
                                <li><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/images/images.jpg'), ['alt' => 'image', 'class'=>"clasadimage"]); ?></li>
                            <?php endif; ?>
                            <?php foreach ($images as $img): ?>
                                <li><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/'. $img->image), ['alt' => Html::encode($model->title), 'class'=>"clasadimage"]); ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                                <li><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/images/images.jpg'), ['alt' => 'image', 'class'=>"clasadimage"]); ?></li>
                        <?php endif; ?>
                    </ul>
                    <?php if (count($images) != 0): ?>
                        <?php if ($allow == 0): ?>
                            <li class="add_image btn-modal-open" data-toggle='modal' data-target="#addimages-modal"><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/images/images.jpg'), ['alt' => 'image', 'class'=>"clasadimage"]); ?></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="add_image btn-modal-open" data-toggle='modal' data-target="#addimages-modal"><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/images/images.jpg'), ['alt' => 'image', 'class'=>"clasadimage"]); ?></li>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5 col-right">
                <?= $form->field($model, 'service')->dropDownList(
                    ArrayHelper::map(Servis::find()->where(['!=', 'parent_id', 0])->orderBy('title')->all(),'id','title'),
                    ['prompt' => 'Выберите сервис']) ?>
                <h1 class="title text-center">
                    <?= $form->field($model, 'title')->textInput(['class'=>'title text-center'])->label(false) ?>
                </h1>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span>Адрес: </span>
                    </div>
                    <div class="col-sm-7">
                        <?= $form->field($model, 'address')->textarea(['id'=>'address', 'class'=>'text-center selectaddress', 'value'=>(Properties::findOne(['field_id' => 5, 'object_id'=>$model->id]))?Properties::findOne(['field_id' => 5, 'object_id'=>$model->id])->field_value:$model->address])->label(false) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <span>Телефоны: </span>
                    </div>
                    <div class=" col-sm-7">
                        <?= $form->field($model, 'phone')->textInput(['class'=>'text-center','value'=>(Properties::findOne(['field_id'=>7, 'object_id'=>$model->id]))?Properties::findOne(['field_id'=>7, 'object_id'=>$model->id])->field_value:$model->phone])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="texttop newpage">
            <div class="row">
                <div class="col-sm-4">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span>Функционирует: </span>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <select class="form-control" id="selectf">
                            <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>32])->field_value != 'круглогодично'): ?>
                                <option value="default">круглогодично</option>
                                <option value="check" selected id="selectp">Выбрать период</option>
                            <?php else: ?>
                                <option value="default">круглогодично</option>
                                <option value="check">Выбрать период</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <?= $form->field($model, 'field32')->hiddenInput()->label(false) ?>
                    <div class="check-period" style="display:none;">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="from">с</label>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <select class="form-control" id="from1">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="from2">
                                                <option value="января">январь</option>
                                                <option value="февраля">февраль</option>
                                                <option value="марта">март</option>
                                                <option value="апреля">апрель</option>
                                                <option value="мая">май</option>
                                                <option value="июня">июнь</option>
                                                <option value="июля">июль</option>
                                                <option value="августа">август</option>
                                                <option value="сентября">сентябрь</option>
                                                <option value="октября">октябрь</option>
                                                <option value="ноября">ноябрь</option>
                                                <option value="декабря">декабрь</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 col-sm-offset-1">
                                <div class="form-group">
                                    <label for="from">по</label>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <select class="form-control" id="to1">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="to2">
                                                <option value="января">январь</option>
                                                <option value="февраля">февраль</option>
                                                <option value="марта">март</option>
                                                <option value="апреля">апрель</option>
                                                <option value="мая">май</option>
                                                <option value="июня">июнь</option>
                                                <option value="июля">июль</option>
                                                <option value="августа">август</option>
                                                <option value="сентября">сентябрь</option>
                                                <option value="октября">октябрь</option>
                                                <option value="ноября">ноябрь</option>
                                                <option value="декабря">декабрь</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row metre">
                <?php if ($cat_config == 1): ?>
                    <div class="col-lg-4">
                        <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>1])): ?>
                            <?= $form->field($model, 'field1', ['template' => "{label}\n{input}<span>метров</span>\n{hint}\n{error}"])->input('number', ['value'=>Properties::findOne(['object_id'=>$model->id, 'field_id'=>1])->field_value])->label('Расстояние до моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к морским курортам.']) ?>
                        <?php else: ?>
                            <?= $form->field($model, 'field1', ['template' => "{label}\n{input}<span>метров</span>\n{hint}\n{error}"])->input('number')->label('Расстояние до моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к морским курортам.']) ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="col-lg-4">
                        <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>34])): ?>
                            <?= $form->field($model, 'field34', ['template' => "{label}\n{input}<span>метров</span>\n{hint}\n{error}"])->input('number', ['value'=>Properties::findOne(['object_id'=>$model->id, 'field_id'=>34])->field_value])->label('Высота над уровнем моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к горным курортам.']) ?>
                        <?php else: ?>
                            <?= $form->field($model, 'field34', ['template' => "{label}\n{input}<span>метров</span>\n{hint}\n{error}"])->input('number')->label('Высота над уровнем моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к горным курортам.']) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="selectcc">Расстояние до центра:</label>
                        <select class="form-control" id="selectcc">
                            <option value="">Выбрать</option>
                            <option value="default" <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])&&Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])->field_value=="000") ? 'selected' : ''?>>В центре</option>
                            <option value="check" <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])&&Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])->field_value!="000") ? 'selected' : ''?>>Указать расстояние</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])&&Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])->field_value!="000") ? '' : 'displaynone'?> from-center">
                    <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])): ?>
                        <?= $form->field($model, 'field2')->input('number', ['value'=>Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])->field_value])->label("метров") ?>
                    <?php else: ?>
                        <?= $form->field($model, 'field2')->input('number', ['value'=>''])->label("метров") ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span>Основное: </span>
                </div>
                <div class="col-sm-8">
                    <?= $form->field($model, 'general')->textarea(['rows'=>2, 'maxlength'=>"200"])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-3 col-sm-offset-5">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-common btn-save-update']) ?>
            </div>
        </div>

    </div>
<?php ActiveForm::end(); ?>
<?php
$functional = Properties::findOne(['object_id'=>$model->id, 'field_id'=>32]);
$js = "
$('#selectf').change(function(){
    if ($(this).val()=='default') {
        $('.check-period').fadeOut();
        $('#object-field32').val('круглогодично');
    } else {
        $('.check-period').fadeIn();
        $('#object-field32').val('с '+$('#from1').val()+' '+$('#from2').val()+' по '+$('#to1').val()+' '+$('#to2').val());
    }
})
$('#selectcc').change(function(){
    if ($(this).val()=='') {
        $('#object-field2').val('');
        $('.from-center').addClass('displaynone');
    } else if ($(this).val()=='default') {
        $('#object-field2').val('000');
        $('.from-center').addClass('displaynone');
    } else {
        $('.from-center').removeClass('displaynone');
        $('#object-field2').val('');
    }
})
$('.check-period .form-control').each(function(){
    $(this).change(function(){
        $('#object-field32').val('с '+$('#from1').val()+' '+$('#from2').val()+' по '+$('#to1').val()+' '+$('#to2').val());
    })
})";
$this->registerJs($js);
if ($functional->field_value != "круглогодично") {
    $jsfunc = "$('.check-period').show();
    $('#from1').val(".explode(" ",$functional->field_value )[1].");
    $('#from2').val('".explode(' ',$functional->field_value )[2]."');
    $('#to1').val(".explode(" ",$functional->field_value )[4].");
    $('#to2').val('".explode(' ',$functional->field_value )[5]."');";
    $this->registerJs($jsfunc);
}
if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])) {
    $jsfunc1 = "$(document).ready(function(){
        if ($('#selectcc').val()=='') {
            $('#object-field2').val('');
        } else if ($('#selectcc').val()=='default') {
            $('#object-field2').val('000');
        } else {
            $('#object-field2').val('".Properties::findOne(['object_id'=>$model->id, 'field_id'=>2])->field_value."');
        }
    })";
    $this->registerJs($jsfunc1);
}
if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>32])) {
    $jsfunc32 = "$(document).ready(function(){
        if ($('#selectf').val()=='default') {
            $('#object-field32').val('круглогодично');
        } else {
            $('#object-field32').val('".Properties::findOne(['object_id'=>$model->id, 'field_id'=>32])->field_value."');
        }
    })";
    $this->registerJs($jsfunc32);
}
?>
<script type="text/javascript">
function alertfunction(x) {
    $("#hiddenimg").val(x);
    $('#delete-modal').modal('show');
}
function deletebutton() {
    $('#delete-modal').modal('hide');
}

function alertfunction1(x) {
    $("#hiddenimg1").val(x);
    $('#header-modal').modal('show');
}
function deletebutton1() {
    $('#header-modal').modal('hide');
}
function checkedfunc() {
    $('#checkallow').val(0);
    $('#update-form').submit();
}
function nocheckedfunc() {
    $('#checkallow').val(1);
    $('#update-form').submit();
}
function handleFileSelect(evt) {
    var files = evt.target.files;
    for (var i = 0, f; f = files[i]; i++) {
        if (!f.type.match('image.*')) {
            continue;
        }
        var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
                var div = document.createElement('div');
                div.className = "divimages";
                $('#list .divimages').remove();
                div.innerHTML = ['<img class="thumb" src="', e.target.result,
                        '" title="', escape(theFile.name), '"/>'].join('');
                document.getElementById('list').insertBefore(div, null);
            };
        })(f);
        reader.readAsDataURL(f);
        $("#text_value_image").show();
    }
}
var filesExists = document.getElementById("files");
if (filesExists) {
    filesExists.addEventListener('change', handleFileSelect, false);
}
</script>
