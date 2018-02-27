<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Config;
use yii\bootstrap\Modal;

Modal::begin([
    'id'=>'delete-modal',
    'size'=>'modal-sm',
    'header' => '<h3 class="modal-title text-center">Уведомление</h3>',
]);?>
<div id="address-content">
    <?php $form = ActiveForm::begin([
        'id'=>'deleteform',
        'action'=>['deleteimage'],
    ]); ?>
        <h3 class="text-center">Вы уверены,что хотите удалить?</h3>
        <div class="form-group text-center">
            <?= Html::submitButton('ДА', ['class' => 'btn btn-common', 'name' => 'delete-button']) ?>
            <?= Html::button('НЕТ', ['class' => 'btn btn-common', 'onclick' => 'deletebutton()']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php Modal::end();
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'alias')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'alias_two')->textInput() ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'alias_three')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <label>Лого</label>
            <i class="fa fa-trash" aria-hidden="true" onclick="alertfunction()" title="Удалить лого"></i>
            <div class="logo-img" id="logo-img">
                <?php if (Config::findOne(['id'=>1])->logo): ?>
                    <img src="<?=Yii::$app->request->baseUrl."/".Config::findOne(['id'=>1])->logo?>" alt="">
                <?php else: ?>
                    <img src="<?=Yii::$app->request->baseUrl."/upload/logo/defaultlogo/logo.png"?>" alt="">
                <?php endif; ?>
                <?= $form->field($model, 'file')->fileInput(['id' => 'config-file'])->label(false) ?>
            </div>
        </div>
        <div class="col-sm-6">
            <label>Водной знак</label>
            <div class="logo-img">
                <?php if (Config::findOne(['id'=>1])->watermark): ?>
                    <img src="<?=Yii::$app->request->baseUrl."/".Config::findOne(['id'=>1])->watermark?>" alt="">
                <?php else: ?>
                    <img src="<?=Yii::$app->request->baseUrl."/upload/logo/defaultlogo/watermark.svg"?>" alt="">
                <?php endif; ?>
                <?= $form->field($model, 'water')->fileInput(['id' => 'watermark-config'])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <label>Выберите тип нас. пункта</label>
            <?= $form->field($model, 'objectcat_id')->radioList([1 => 'Морской', 2 => 'Горный'])->label(false) ?>
        </div>
        <div class="col-sm-6 text-right">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
function alertfunction() {
    $('#delete-modal').modal('show');
}
function deletebutton() {
    $('#delete-modal').modal('hide');
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
        if (!$("#logo-img div:last-child").hasClass('form-group')) {
            $("#logo-img div:last-child").remove();
        }
          var div = document.createElement('div');
          div.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/><i class="fa fa-times" aria-hidden="true" title="Удалить"></i>'].join('');
          document.getElementById('logo-img').insertBefore(div, null);
    };
  })(f);
  reader.readAsDataURL(f);
}
}
document.getElementById('config-file').addEventListener('change', handleFileSelect, false);
</script>
