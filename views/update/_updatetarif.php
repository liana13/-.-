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
use borales\extensions\phoneInput\PhoneInput;
use app\models\Curency;
use app\models\Servis;
use app\models\Food;
use dosamigos\tinymce\TinyMce;
use app\models\Config;
use app\models\Weekdays;

$address = Config::findOne(["id"=>1])->address.", ".Config::findOne(["id"=>1])->title;
$cat_config = Config::findOne(["id"=>1])->objectcat_id;
$days = [1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 0 => 'Вс'];
$wds = Weekdays::findOne(['object_id'=>$model->id]);
if ($wds) {
    $model->weekdays = explode(',', $wds->week_days);
}
$config = $address.", ";
$street = explode($config, $model->address)[1];
$tarifid = $model->new_tarif;
$fields = Field::find()->where(['!=', 'class', ""])->orderBy('sort asc')->all();
$image = new Image();
if ($model->tarif_id==4) {
    $imgcount = explode(",",Tarif::findOne(['tarifid'=>4])->photo)[0];
} else {
    $imgcount = Tarif::findOne(['tarifid'=>$model->new_tarif])->photo;
}
$images=Image::find()->where(['object_id'=>$model->id])->orderBy('id')->limit($imgcount)->all();
$tarif = $model->new_tarif;
$tarif_images = Tarif::findOne(['tarifid'=>$tarif]);
if ($tarif == 0) {
    if (count($images) == $tarif_images->photo) {
        $allow = 1;
    } else {
        $allow = 0;
    }
} elseif ($tarif == 1) {
    if (count($images) == $tarif_images->photo) {
        $allow = 1;
    } else {
        $allow = 0;
    }
} elseif ($tarif == 2) {
    if (count($images) == $tarif_images->photo) {
        $allow = 1;
    } else {
        $allow = 0;
    }
}  elseif ($tarif == 3) {
    if (count($images) == $tarif_images->photo) {
        $allow = 1;
    } else {
        $allow = 0;
    }
}
elseif ($tarif == 4) {
    if (count($images) == explode(",",$tarif_images->photo)[0]) {
        $allow = 1;
    } else {
        $allow = 0;
    }
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
         <?= $form->field($model, 'address')->textarea(['id'=>'address-value', 'value'=>$street])->label(false) ?>
        <div class="form-group text-center">
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
    'id'=>'header-modal',
    'size'=>'modal-sm',
    'header' => '<h3 class="modal-title text-center">Уведомление</h3>',
]);?>
<div id="header-content">
    <?php $form = ActiveForm::begin([
        'id'=>'deleteform',
        'action'=>['/update/headerimage/'.$model->id],
    ]); ?>
        <?= $form->field($model, 'img')->hiddenInput(['id'=>'hiddenimg1'])->label(false) ?>
        <h3 class="text-center">Хотите назначить это изображеие главным?</h3>
        <div class="form-group text-center">
            <?= Html::submitButton('ДА', ['class' => 'btn btn-common', 'name' => 'delete-button']) ?>
            <?= Html::button('НЕТ', ['class' => 'btn btn-common', 'onclick' => 'deletebutton1()']) ?>
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
        </div>
    </div>
    <div class="form-group text-center">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-common']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Modal::end();?>
<?php $form = ActiveForm::begin(['options'=>['id'=>'update-form']]); ?>
    <div class="object-update">
        <div class="row">
            <div class="col-lg-7">
                <div id="slider" class="flexslider <?=(count($images)==1 || count($images)==1) ? 'single' : ''?>">
                    <ul class="slides">
                        <?php if (Image::find()->where(['object_id'=>$model->id])): ?>
                            <?php if (count($images) != 0): ?>
                                <?php foreach ($images as $img): ?>
                                    <li><?= Html::img(Html::encode(Yii::$app->request->baseUrl.'/'. $img->image), ['alt' => Html::encode($model->title), 'class'=>"clasadimage"]); ?>
                                        <i class="fa fa-trash" aria-hidden="true" onclick="alertfunction(<?=$img->id?>)" title="Удалить"></i>
                                    </li>
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
                    <div class="col-sm-7">
                        <?= $form->field($model, 'phone')->textInput(['placeholder' => '+0000000000', 'maxlength' => true,
                            'value'=>(Properties::findOne(['field_id'=>7, 'object_id'=>$model->id]))?Properties::findOne(['field_id'=>7, 'object_id'=>$model->id])->field_value:$model->phone])->label(false);?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <span>Функционирует: </span>
                    </div>
                    <div class="col-sm-7">
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
                        <?= $form->field($model, 'field32')->hiddenInput(['value' => 'круглогодично'])->label(false) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="check-period" style="display:none;">
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
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
                <div class="row metre tarif">
                    <?php if ($cat_config == 1): ?>
                        <div class="col-lg-12">
                            <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>1])): ?>
                                <?= $form->field($model, 'field1', ['template' => "<div class='row'><div class='col-md-6'>{label}</div>\n<div class='col-md-6'>{input}<span>метров</span>\n{hint}\n{error}</div></div>"])->input('number', ['value'=>Properties::findOne(['object_id'=>$model->id, 'field_id'=>1])->field_value])->label('Расстояние до моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к морским курортам.']) ?>
                            <?php else: ?>
                                <?= $form->field($model, 'field1', ['template' => "<div class='row'><div class='col-md-6'>{label}</div>\n<div class='col-md-6'>{input}<span>метров</span>\n{hint}\n{error}</div></div>"])->input('number')->label('Расстояние до моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к морским курортам.']) ?>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-12">
                            <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>34])): ?>
                                <?= $form->field($model, 'field34', ['template' => "<div class='row'><div class='col-md-6'>{label}</div>\n<div class='col-md-6'>{input}<span>метров</span>\n{hint}\n{error}</div></div>"])->input('number', ['value'=>Properties::findOne(['object_id'=>$model->id, 'field_id'=>34])->field_value])->label('Высота над уровнем моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к горным курортам.']) ?>
                            <?php else: ?>
                                <?= $form->field($model, 'field34', ['template' => "<div class='row'><div class='col-md-6'>{label}</div>\n<div class='col-md-6'>{input}<span>метров</span>\n{hint}\n{error}</div></div>"])->input('number')->label('Высота над уровнем моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к горным курортам.']) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-lg-8">
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
                <?php if ($model->tarif_id == 4): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="cust-checkbox texttop pull-right">
                                <?php if ($model->allow_review ==1): ?>
                                    <input type="checkbox" checked name="Object[allow_review]">
                                    <span onclick="checkedfunc()"></span>
                                    <span onclick="checkedfunc()">Разрешить писать отзывы об этом объекте</span>
                                <?php else: ?>
                                    <input type="checkbox" name="Object[allow_review]">
                                    <span onclick="nocheckedfunc()"></span>
                                    <span onclick="nocheckedfunc()">Разрешить писать отзывы об этом объекте</span>
                                <?php endif; ?>
            				</label>
                        </div>
                    </div>
                    <?= $form->field($model, 'allow_review')->hiddenInput(['id'=>'checkallow'])->label(false) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="texttop newpage">
            <div class="row">
                <div class="col-sm-5">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span>Основное: </span>
                </div>
                <div class="col-sm-7">
                    <?= $form->field($model, 'general')->textarea(['rows'=>2, 'maxlength'=>"200"])->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span>Описание: </span>
                </div>
                <div class="col-sm-7">
                    <?= $form->field($model, 'description')->textarea()->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <label>Координаты GPS (Укажите GPS координаты объекта):</label>
                </div>
                <div class="col-sm-6">
                    <?php if (Properties::findOne(['field_id'=>6, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'field6')->textInput(['placeholder'=>"N;E", 'value'=>Properties::findOne(['field_id'=>6, 'object_id'=>$model->id])->field_value])->label(false) ?>
                    <?php else: ?>
                        <?= $form->field($model, 'field6')->textInput(['placeholder'=>"N;E"])->label(false) ?>
                    <?php endif; ?>
                </div>
                <?php if ($tarifid != 4): ?>
                    <div class="col-sm-6">
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <label>Адрес в интернет (Если имеется сайт объекта, то напишите его название):</label>
                    </div>
                    <div class="col-sm-6">
                        <?php if (Properties::findOne(['field_id'=>9, 'object_id'=>$model->id])): ?>
                            <?= $form->field($model, 'website')->textInput(['placeholder'=>"http://", 'value'=>Properties::findOne(['field_id'=>9, 'object_id'=>$model->id])->field_value])->label(false) ?>
                        <?php else: ?>
                            <?= $form->field($model, 'website')->textInput(['placeholder'=>"http://"])->label(false) ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <hr class="hr-object">
            <div class="row">
                <div class="col-sm-3">
                    <?php if (Properties::findOne(['field_id'=>12, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'price', ['template' => "{label}\n{input}<span>чел./сут.</span>\n{hint}\n{error}"])->input('number', ['value'=>Properties::findOne(['field_id'=>12, 'object_id'=>$model->id])->field_value])->label('Минимальная цена (от)')?>
                    <?php else: ?>
                        <?= $form->field($model, 'price', ['template' => "{label}\n{input}<span>чел./сут.</span>\n{hint}\n{error}"])->input('number')->label('Минимальная цена (от)')?>
                    <?php endif; ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, 'curency_id')->dropDownList(
                        ArrayHelper::map(Curency::find()->all(),'id','mini_title'),
                       ['prompt' => 'Выберите валюту'])->label('Валюта') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($model, 'food_id')->radioList(ArrayHelper::map(Food::find()->all(),'id','title'))->label(false) ?>
                </div>
            </div>
            <hr class="hr-object">
            <div class="row">
                <div class="col-sm-5">
                    <i class="fa fa-arrows-h" aria-hidden="true"></i>
                    <span data-toggle='tooltip' title='Напишите информацию по размещению: Сколько номеров, какие удобства, вместимость и пр.'>Размещение <i class="fa fa-question-circle" aria-hidden="true"></i></span>
                </div>
                <div class="col-sm-7">
                    <?php if (Properties::findOne(['field_id'=>11, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'field11')->textarea(['rows'=>3, 'value'=> Properties::findOne(['field_id'=>11, 'object_id'=>$model->id])->field_value])->label(false); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'field11')->textarea(['rows'=>3])->label(false); ?>
                    <?php endif; ?>
                </div>
                <div class="col-sm-2 onesm">
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    <span><?=Field::findOne(['id'=>49])->title?>: </span>
                </div>
                <div class="col-sm-3">
                    <?php if (Properties::findOne(['field_id'=>49, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'zaezdto')->textInput(['class'=>'text-center form-control', 'value'=> Properties::findOne(['field_id'=>49, 'object_id'=>$model->id])->field_value])->label(false) ?>
                    <?php else: ?>
                        <?= $form->field($model, 'zaezdto')->textInput(['class'=>'text-center form-control', 'placeholder'=>'__.__'])->label(false) ?>
                    <?php endif; ?>
                </div>
                <div class="col-sm-2 twosm">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    <span><?=Field::findOne(['id'=>50])->title?>: </span>
                </div>
                <div class="col-sm-3">
                    <?php if (Properties::findOne(['field_id'=>50, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'viezd')->textInput(['class'=>'text-center form-control', 'value'=> Properties::findOne(['field_id'=>50, 'object_id'=>$model->id])->field_value])->label(false) ?>
                    <?php else: ?>
                        <?= $form->field($model, 'viezd')->textInput(['class'=>'text-center form-control', 'placeholder'=>'__.__'])->label(false) ?>
                    <?php endif; ?>
                </div>
                <?php if ($tarifid == 4): ?>
                    <div class="col-sm-12 weekdays-box">
                        <?= $form->field($model, 'weekdays')->checkboxList($days)->label('Выберите, какие дни считать выходными', ['data-toggle'=>'tooltip', 'title'=>'Например, поставив выбор на пт. Это значит что сутки с пятницы по субботу будут считаться системой как выходные']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($tarifid != 4): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span data-toggle='tooltip' title='Вы можете добавить один или несколько прайс-листов и назвать их. При заполнении таблицы (прайс-листа) в ячейки с текстом не вписывайте цифры т.к все ячейки в которых будут вписаны цифры будут обработаны конвертером валют для перевода в другую денежную единицу и цифры в них изменятся.'> Цены <i class="fa fa-question-circle" aria-hidden="true"></i></span>
                    </div>
                    <div class="col-sm-7">
                        <?php if (Properties::findOne(['field_id'=>31, 'object_id'=>$model->id])): ?>
                            <?= $form->field($model, 'price_property')->widget(TinyMCE::className(), [
                                'options'=>[
                                    'value'=>Properties::findOne(['field_id'=>31, 'object_id'=>$model->id])->field_value,
                                ],
                                'language' => 'ru',
                                'clientOptions' => [
                                    'height' => 300,
                                    'image_dimensions' => false,
                                    'statusbar' => false,
                                    'plugins' => [
                                        'table', 'code',
                                    ],
                                    'table_default_styles'=> [
                                        'width'=>'200px',
                                    ],
                                    'menubar' => 'table',
                                    'table_toolbar' => false,
                                    'toolbar' => false,
                                    'menu' => [
                                        'table' => ['title'=>'Table', 'items'=>'inserttable deletetable | cell row column'],
                                    ],
                                ],
                            ])->label(false); ?>
                           <?php else: ?>
                                <?= $form->field($model, 'price_property')->widget(TinyMCE::className(), [
                                    'language' => 'ru',
                                    'clientOptions' => [
                                        'height' => 300,
                                        'image_dimensions' => false,
                                        'statusbar' => false,
                                        'plugins' => [
                                            'table', 'code',
                                        ],
                                        'table_default_styles'=> [
                                            'width'=>'200px',
                                        ],
                                        'menubar' => 'table',
                                        'table_toolbar' => false,

                                        'toolbar' => false,
                                        'menu' => [
                                            'table' => ['title'=>'Table', 'items'=>'inserttable deletetable | cell row column'],
                                        ],
                                    ],
                                ])->label(false); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <hr class="hr-object">
            <div class="row">
                <div class="col-lg-4">
                    <?php if (Properties::findOne(['field_id'=>36, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'phone_booking')->textInput(['placeholder' => '+0000000000', 'onkeypress'=>"return check(event);", 'value'=>Properties::findOne(['field_id'=>36, 'object_id'=>$model->id])->field_value])->label('Номер моб. телефона для смс уведомлений <i class="fa fa-question-circle" aria-hidden="true"></i>',['data-toggle'=>'tooltip', 'title'=>'Укажите один номер мобильного телефона в международном формате (без пробелов, тире, скобок. Например: +79121234567) . На этот номер будут приходить СМС уведомления о бронировании.']);?>
                    <?php else: ?>
                        <?= $form->field($model, 'phone_booking')->textInput(['placeholder' => '+0000000000', 'onkeypress'=>"return check(event);"])->label('Номер моб. телефона для смс уведомлений <i class="fa fa-question-circle" aria-hidden="true"></i>',['data-toggle'=>'tooltip', 'title'=>'Укажите один номер мобильного телефона в международном формате (без пробелов, тире, скобок. Например: +79121234567) . На этот номер будут приходить СМС уведомления о бронировании.']);?>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <?php if (Properties::findOne(['field_id'=>38, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'email_booking')->textInput(['value'=>Properties::findOne(['field_id'=>38, 'object_id'=>$model->id])->field_value])->label('Эл. почта для уведомлений о бронировании <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'На этот E-mail будут приходить уведомления о бронировании.']); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'email_booking')->textInput()->label('На этот E-mail будут приходить уведомления о бронировании. <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'На этот E-mail будут приходить уведомления о бронировании.']); ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <?php if (Properties::findOne(['field_id'=>39, 'object_id'=>$model->id])): ?>
                        <?= $form->field($model, 'emailsms')->textInput(['value'=>Properties::findOne(['field_id'=>39, 'object_id'=>$model->id])->field_value])->label('Эл. почта для уведомлений о сообщениях от клиентов <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'На этот E-mail будут приходить уведомления о сообщениях или вопросах от клиентов.']); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'emailsms')->textInput()->label('Эл. почта для уведомлений о сообщениях от клиентов. <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'На этот E-mail будут приходить уведомления о сообщениях или вопросах от клиентов.']); ?>
                    <?php endif; ?>
                </div>
            </div>
            <hr class="hr-object">
            <?php foreach ($fields as $field): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa <?=$field->class?>" aria-hidden="true"></i>
                        <span><?=$field->title?>: </span>
                    </div>
                    <?php $x='field'.$field->id; ?>
                    <div class="col-sm-7">
                        <?php if (Properties::findOne(['field_id'=>$field->id, 'object_id'=>$model->id])): ?>
                            <?php if ($field->id == 6): ?>
                                <?= $form->field($model, $x)->textarea(['value'=>Properties::findOne(['field_id'=>$field->id, 'object_id'=>$model->id])->field_value, 'placeholder'=>"N;E"])->label(false) ?>
                            <?php elseif ($field->id == 33): ?>
                                <?php  $list = ['1'=> 'Одна', '2' => 'Две', '3'=>"Три", '4'=>"Четыре", '5'=>"Пять"]; ?>
                                <?= $form->field($model, $x)
                                        ->dropDownList($list, ['prompt' => 'Нет'])
                                        ->label(false);?>
                            <?php else: ?>
                                <?= $form->field($model, $x)->textarea(['value'=>Properties::findOne(['field_id'=>$field->id, 'object_id'=>$model->id])->field_value])->label(false) ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($field->id == 6): ?>
                                <?= $form->field($model, $x)->textarea(['placeholder'=>"N;E"])->label(false) ?>
                            <?php else: ?>
                                <?= $form->field($model, $x)->textarea()->label(false) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <h5>Расположение на карте (Вы можете обозначить на карте размещаемый объект.)</h5>
        <div id="mapadd"  style="width: 700px; height: 300px"> </div>
        <?php if (Properties::findOne(['field_id'=>4, 'object_id'=>$model->id])): ?>
            <input type="hidden" value="<?=Properties::findOne(['field_id'=>4, 'object_id'=>$model->id])->field_value?>" id="coordinatesmaps" name="Object[propertygprs]"  style="width:300px;">
        <?php else: ?>
            <input type="hidden" value="" id="coordinatesmaps" name="Object[propertygprs]"  style="width:300px;">
        <?php endif; ?>
        <div class="row">
            <div class="form-group col-sm-3 col-sm-offset-5">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-common btn-save-update']) ?>
            </div>
        </div>

    </div>
<?php ActiveForm::end(); ?>
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
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
if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>4])) {
    $jsmap = '$(document).ready(function(){
        var addmap = 1;
        ymaps.ready(initadd);
        function initadd () {
            var myMap = new ymaps.Map("mapadd", {
                center: '.Properties::findOne(['object_id'=>$model->id, 'field_id'=>4])->field_value.',
                zoom: 8
            }),
            myPlacemark = new ymaps.Placemark('.Properties::findOne(['object_id'=>$model->id, 'field_id'=>4])->field_value.', {
                hintContent: "Подвинь меня!"
            }, {
                draggable: true
            });
            myMap.controls
                .add("typeSelector")
                .add("smallZoomControl", { right: 5, top: 75 })
                .add("mapTools");
            myMap.geoObjects.add(myPlacemark);
            myPlacemark.events.add("dragend", function () {
                $("#coordinatesmaps").val("["+myPlacemark.geometry.getCoordinates()+"]");
            });
        }
    })';
    $this->registerJs($jsmap);

} else {
    $jsmap = '$(document).ready(function(){
        var addmap = 1;
        ymaps.ready(initadd);
        function initadd () {
            var myMap = new ymaps.Map("mapadd", {
                center: [45.086123611884815, 38.74257812499972],
                zoom: 8
            }),
            myPlacemark = new ymaps.Placemark([45.086123611884815, 38.74257812499972], {
                hintContent: "Подвинь меня!"
            }, {
                draggable: true
            });
            myMap.controls
                .add("typeSelector")
                .add("smallZoomControl", { right: 5, top: 75 })
                .add("mapTools");
            myMap.geoObjects.add(myPlacemark);
            myPlacemark.events.add("dragend", function () {
                $("#coordinatesmaps").val("["+myPlacemark.geometry.getCoordinates()+"]");
            });
        }
    })';
    $this->registerJs($jsmap);

}
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
function check(event) {
    if (event.keyCode==32) {
        return false;
    }
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
