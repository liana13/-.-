<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\ObjectForm;
use app\models\Tarif;
use app\models\Food;
use app\models\Servis;
use app\models\Image;
use app\models\Curency;
use dosamigos\tinymce\TinyMce;
use borales\extensions\phoneInput\PhoneInput;
use app\models\Config;

$address = Config::findOne(["id"=>1])->address.", ".Config::findOne(["id"=>1])->title;
$tarifid = Tarif::findOne(['id'=>Yii::$app->request->get('id')])->tarifid;
$cat_config = Config::findOne(["id"=>1])->objectcat_id;
?>
<div class="object-form">
    <div class="create-object">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'tarif_id')->hiddenInput(['value' => $tarifid])->label(false);?>
            <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false);?>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'service')->dropDownList(
                        ArrayHelper::map(Servis::find()->where(['!=', 'parent_id', 0])->orderBy('title')->all(),'id','title'),
                        ['prompt' => 'Выберите сервис']) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'address_config')->textInput(['id'=>'address', 'value'=>$address, 'disabled'=>true])->label('Адрес') ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'address')->textInput(['id'=>'address'])->label('Улица* <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Добавьте улицу и номер дома или другие указания на местоположение объекта.']) ?>
                </div>
            </div>
            <hr class="hr-object">
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+0000000000', 'maxlength' => true])->label("Телефоны*"); ?>
                </div>
                <div class="col-sm-2 col-sm-offset-1">
                    <div class="form-group">
                        <label for="selectf">Функционирует:</label>
                        <select class="form-control" id="selectf">
                            <option value="default">круглогодично</option>
                            <option value="check">Выбрать период</option>
                        </select>
                    </div>
                    <?= $form->field($model, 'field111')->hiddenInput(['value' => 'круглогодично'])->label(false) ?>
                </div>
                <div class="col-sm-6">
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
            <hr class="hr-object">
            <div class="row">
                <div class="metre">
                    <?php if ($cat_config == 1): ?>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'field222', ['template' => "{label}\n{input}<span>метров</span>\n{hint}\n{error}"])->input('number')->label('Расстояние до моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к морским курортам.']) ?>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'field333', ['template' => "{label}\n{input}<span>метров</span>\n{hint}\n{error}"])->input('number')->label('Высота над уровнем моря <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Заполняется только цифрами. Для объектов относящихся к горным курортам.']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="selectc">Расстояние до центра:</label>
                        <select class="form-control" id="selectcc">
                            <option value="">Выбрать</option>
                            <option value="default">В центре</option>
                            <option value="check">Указать расстояние</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 displaynone from-center">
                    <?= $form->field($model, 'field444')->input('number', ['value'=>''])->label("метров") ?>
                </div>
            </div>
            <hr class="hr-object">
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($model, 'general')->textarea(['rows'=>2, 'maxlength'=>"200"])->label('Основное* <i class="fa fa-question-circle" aria-hidden="true"></i>', ['data-toggle'=>'tooltip', 'title'=>'Опишите вкратце объект, услуги и т.д. Данное описание выводится как на личной странице, так и в общем списке объектов.']) ?>
                </div>
            </div>
            <div class="fileimgrow row">
                <div class="col-lg-12">
                    <div class="fileimg col-sm-4">
                        <span class="fileInputobj"><i class="fa fa-download" aria-hidden="true"></i> Добавить изображение</span>
                        <?=$form->field($model, 'file')->fileInput(['id' => 'files'])->label(false)?>
                    </div>
                    <div id="list" class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <?=$form->field($model, 'value_image')->textInput(['id' => 'text_value_image', 'placeholder'=>'Описание фото'])->label(false)?>
                        <input type='hidden' name=ObjectForm[main_image][] value="1">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-common']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$js = "$('#objectform-field222').change(function(){
    if ($(this).val()!='') {
        $('#objectform-field333').attr('disabled', true);
    } else {
        $('#objectform-field333').attr('disabled', false);
    }
})
$('#objectform-field333').change(function(){
    if ($(this).val()!='') {
        $('#objectform-field222').attr('disabled', true);
    } else {
        $('#objectform-field222').attr('disabled', false);
    }
}),
$('#selectf').change(function(){
    if ($(this).val()=='default') {
        $('.check-period').fadeOut();
        $('#objectform-field111').val('круглогодично');
    } else {
        $('.check-period').fadeIn();
        $('#objectform-field111').val('с '+$('#from1').val()+' '+$('#from2').val()+' по '+$('#to1').val()+' '+$('#to2').val());
    }
})
$('#selectcc').change(function(){
    if ($(this).val()=='') {
        $('#objectform-field444').val('');
        $('.from-center').hide();
    } else if ($(this).val()=='default') {
        $('#objectform-field444').val('000');
        $('.from-center').hide();
    } else {
        $('.from-center').show();
        $('#objectform-field444').val('');
    }
})
$('.check-period .form-control').each(function(){
    $(this).change(function(){
        $('#objectform-field111').val('с '+$('#from1').val()+' '+$('#from2').val()+' по '+$('#to1').val()+' '+$('#to2').val());
    })
})";
$this->registerJs($js);
?>
<script>
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
