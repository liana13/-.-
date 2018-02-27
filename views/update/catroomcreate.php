<?php
use yii\helpers\Html;
use app\models\Image;
use app\models\Person;
use app\models\Review;
use app\models\User;
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\Catroom;
use app\models\Field;
use app\models\Food;
use app\models\Tarif;

$array = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17'];
if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppOwnerobjectAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$object = Object::findOne(['id'=>Yii::$app->request->get('id')]);
$imgcount = explode(',',Tarif::findOne(['id'=>5])->photo)[1];
// $imgcount = 5;

$this->title = Yii::t('app', 'Добавить категории номеров');
$this->params['breadcrumbs'][] = $this->title;
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
    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>
    <?= $this->render(
        '_left.php',
        ['object' => $object]
    ) ?>
    <div class="content-wrapper">
        <section class="content">
            <?= Alert::widget() ?>
            <div class="catroom-form">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'object_id')->hiddenInput(['value'=>$object->id])->label(false) ?>
                <?= $form->field($model, 'user_id')->hiddenInput(['value'=>$object->user_id])->label(false) ?>
                <div class="row">
                    <div class="col-sm-8">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Название Категории номеров', ['data-toggle'=>'tooltip', 'title'=>' например: Двухместнуй стандарт, или Спальное место в шестиместном номере(для хостелов)']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?= $form->field($model, 'room_count')->input('number')->label('Количество комнат в номере', ['data-toggle'=>'tooltip', 'title'=>'не считая санузла и ванной']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                       <?= $form->field($model, 'adult_count')->input('number', ['max'=>10, 'min'=>1])->label('Взрослых', ['data-toggle'=>'tooltip', 'title'=>'Максимальная вместимость взрослых, не считая доп.мест'])?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'child_count')->input('number', ['max'=>4, 'min'=>0, 'value'=>0, 'class'=>'child_count'])->label('Детей бесплатно (без доп. места)', ['data-toggle'=>'tooltip', 'title'=>'Выберите кол-во детей и их возраст, которые могут проживать в номере бесплатно, без предоставления доп.места'])?>
                    </div>
                    <div class="col-sm-12">
                        <?=$form->field($model, 'child_age1')->dropDownList($array, ['class'=>'form1 displaynone form-control'])->label("Возраст детей", ['data-toggle'=>'tooltip', 'title'=>'Выберите до какого возраста (включительно)'])?>

                        <?=$form->field($model, 'child_age2')->dropDownList($array, ['class'=>'form2 displaynone form-control'])->label("Возраст детей", [ 'class'=>'displaynone label1', 'data-toggle'=>'tooltip', 'title'=>'Выберите до какого возраста (включительно)'])?>

                        <?=$form->field($model, 'child_age3')->dropDownList($array, ['class'=>'form3 displaynone form-control'])->label("Возраст детей", ['class'=>'displaynone label2', 'data-toggle'=>'tooltip', 'title'=>'Выберите до какого возраста (включительно)'])?>

                        <?=$form->field($model, 'child_age4')->dropDownList($array, ['class'=>'form4 displaynone form-control'])->label("Возраст детей", ['class'=>'displaynone label3', 'data-toggle'=>'tooltip', 'title'=>'Выберите до какого возраста (включительно)'])?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <?= $form->field($model, 'add_count')->input('number',['value'=>0])->label('Доп. место',['data-toggle'=>'tooltip', 'title'=>'Возможно ли предоставление дополнительных мест, если да, то выберите сколько']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?= $form->field($model, 'food_id')->dropDownList(ArrayHelper::map(Food::find()->all(),'id','title'))->label('Питание', ['data-toggle'=>'tooltip', 'title'=>'«Если Вы выбираете один из вариантов питания, то и цену за номер в разделе «Цены» тоже указывайте с учетом питания»']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?= $form->field($model, 'count_rooms')->input('number', ['min'=>1, 'class'=>'room_count form-control'])->label("Количество номеров",['data-toggle'=>'tooltip', 'title'=>'Укажите кол-во номеров данной категории'])?>
                    </div>
                </div>
                <div class="row room_name"></div>
                <div class="row">
                    <div class="col-sm-12">
                        <?= $form->field($model, 'description')->textarea()->label('Описание', ['data-toggle'=>'tooltip', 'title'=>'Опишите что находится в номере, есть ли санузел в номере, балкон, какой вид с окна/балкона и пр.'])?>
                    </div>
                </div>
                <hr style="border-top: 1px solid #dedede;">
                <div class="row">
                    <div class="col-sm-12">
                        <h4><label for="">Цены (Здесь указываются основные цены для данной категории. Ценовые периоды указываются в разделе "Цены". Если в разделе "Цены" не указан ценовой период, то цена берется из этих полей.)</label></h4>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <label for="">Цена за номер в сутки (руб.)</label>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'work_day')->input('number', ['min'=>1, 'placeholder'=>'Цена в будние дни'])->label(false)?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'weekend')->input('number', ['min'=>1, 'placeholder'=>'Цена в выходные дни'])->label(false)?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <label for="">Цена за 1 доп. место в сутки (руб.)</label>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'work_add')->input('number', ['min'=>1, 'placeholder'=>'Цена в будние дни'])->label(false)?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'weekend_add')->input('number', ['min'=>1, 'placeholder'=>'Цена в выходные дни'])->label(false)?>
                            </div>
                        </div>
                    </div>

                </div>
                <hr style="border-top: 1px solid #dedede;">
                <div class="fileimgrow row">
                    <div class="col-sm-12">
                        <p>Вы можете загрузить до <?=$imgcount?> фотографий данной категории номера</p>
                        <div class="fileimg col-sm-4">
                            <span class="fileInputobj"><i class="fa fa-download" aria-hidden="true"></i> Добавить изображение</span>
                            <?=$form->field($model, 'file[]')->fileInput(['onchange'=>'checkcount(this)', 'id' => 'files'])->label(false)?>
                        </div>
                        <div id="list" class="col-sm-4"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-3 col-sm-offset-4">
                        <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-common']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <p class="text-center">TvoyRay.ru 2007-2012. <?=Yii::$app->name?> все права защищены &copy; 2012-<?=date('Y')?></p>
    </footer>
</div>
<script type="text/javascript">
// file
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
          div.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/><i class="fa fa-times" onclick="remove(this)" aria-hidden="true" title="Удалить"></i>'].join('');
          document.getElementById('list').insertBefore(div, null);
        };
      })(f);
      reader.readAsDataURL(f);
    }
  }
  var filesExists = document.getElementById("files");
  if (filesExists) {
      filesExists.addEventListener('change', handleFileSelect, false);
  }
  var img = 0;
  function checkcount(elem) {
      var count = parseInt($("#list > div").length);
      if (count >= <?=$imgcount?>-1) {
          $('.fileimg').hide();
      }
      img++;
      var i = img+1;
      if(img < <?=$imgcount?>) {
          $(".fileimgrow").append("<div class='col-lg-12'><div class='fileimg col-sm-4'><span class='fileInputobj'><i class='fa fa-download'></i> Добавить изображение</span><input type='file' onchange='checkcount(this)' id='files"+img+"' name=Catroom[file][]></div><div class='col-sm-4' id='list-"+img+"'></div></div></div>");
          function handleFileSelect(evt) {
              var files = evt.target.files;
              for (var i = 0, f; f = files[i]; i++) {
                  if (!f.type.match('image.*')) {
                      continue;
                  }
                  var reader = new FileReader();
                  reader.onload = (function(theFile) {
                      return function(e) {
                          var i = img-1;
                          var div = document.createElement('div');
                          div.className = "divimages";
                          $('#list-'+i+' .divimages').remove();
                          div.innerHTML = ['<img class="thumb" src="', e.target.result,
                                  '" title="', escape(theFile.name), '"/><i class="fa fa-times" onclick="remove(this)" aria-hidden="true" title="Удалить"></i>'].join('');
                          document.getElementById('list-'+i).insertBefore(div, null);
                      };
                  })(f);
                  reader.readAsDataURL(f);
              }
          }
          var filesExists = document.getElementById("files"+img);
          if (filesExists) {
              filesExists.addEventListener('change', handleFileSelect, false);
          }
      } else {
          $(".fileimgrow").append("<div class='col-lg-12'><p style=\"color: #FF0000;\">Вы можете загрузить не более <?=$imgcount?> фотографий</p></div>");
      }
      $(elem).addClass('disabled');
  }
  function remove(elem) {
      $(elem).parent().parent().parent().remove();
      var count = parseInt($("#list > div").length);
      if (count < <?=$imgcount?>) {
          $('.fileimgrow').show();
      }
  }
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
