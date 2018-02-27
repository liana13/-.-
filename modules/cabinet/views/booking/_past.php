<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use app\models\User;
use app\models\Object;
use app\models\Image;
use app\models\Servis;
use app\models\Properties;
use app\models\Catroom;
use app\models\Childage;
use app\models\Review;

/* @var $this yii\web\View */
/* @var $model app\models\Ads */
$user =  User::findOne(['id'=>$model->user_id]);
$object = Object::findOne(['id'=>$model->object_id]);
$img = Image::find()->where(['object_id'=>$model->object_id])->orderBy('id')->one();
$catroom = Catroom::findOne(['id'=>$model->catroom_id])->title;

if (Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>7])) {
    $phone = Properties::findOne(['object_id'=>$model->object_id, 'field_id'=>7])->field_value;
} else {
    $phone = "";
}
$datediff = strtotime($model->to) - strtotime($model->from);
$tarb = floor($datediff / (60 * 60 * 24));
$model1 = New Review();
?>
<?php if ($model->cancel==0 && $model->from < date("Y-m-d")): ?>
    <div class="row">
        <div class="col-lg-10">
            <div class="item-container item-featured reviews-enabled">
                <div class="content bron">
                    <div class="item-image">
                        <?php if ($img): ?>
                            <img src="<?=Yii::$app->request->baseUrl.'/'. $img->image?>" alt="<?=$model->id?>" width="" height="">
                            <?php else: ?>
                                <img class="" src="<?=Yii::$app->request->baseUrl?>/images/default.jpg">
                        <?php endif; ?>
                    </div>
                    <div class="item-data">
                        <div class="item-header">
                            <label class="activity">
                                <p><b>№<?=$object->id .'-'. $model->id?></b>  <?= Html::a('<span class="label label-rounded label-danger" data-toggle="tooltip" title="Распечатайте ваучер на заселение или сохраните на экран мобильного устройства. Предъявите его при заселении. Просьба сохранять ваучер до конца поездки.">Распечатать ваучер на заселение</span>', "javascript: w=window.open('../default/vaucher/$model->id'); w.print();", ['class'=>'underline']) ?></p>
                            </label>
                            <div class="pull-right">
                                <?php Modal::begin([
                                    'header' => '<h2>Написать отзыв</h2>',
                                    'toggleButton' => ['label' => 'Написать отзыв', 'class'=>'btn btn-warning'],
                                ]);?>
                                <?php if (Object::findOne(['id'=>$model->object_id])->allow_review==1): ?>
                                    <?php $form = ActiveForm::begin([
                                        'action'=>['review/create']
                                    ]); ?>
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <h3>Ник: <span class="text-primary"><?=User::findOne(['id'=>Yii::$app->user->getId()])->username?></span></h3>
                                                <?= $form->field($model1, 'object_id')->hiddenInput(['value'=>$model->object_id])->label(false);?>
                                                <?= $form->field($model1,'user_id')->hiddenInput(['value'=>Yii::$app->user->getId()])->label(false);?>
                                                <?= $form->field($model1, 'locality')->textInput(['maxlength' => true])->label('Напишите, с какого Вы населенного пункта ')?>
                                                <p>Кол-во звезд (оценка объекта)*</p>
                                                <?= $form->field($model1, 'rate')
                                                        ->radioList([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5 ],
                                                            [
                                                                'item' => function($index, $label, $name, $checked, $value) {
                                                                    $return = '<label class="modal-radio">';
                                                                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                                                                    $return .= '<span>' . ucwords($label) . '</span>';
                                                                    $return .= '</label>';
                                                                    return $return;
                                                                }
                                                            ]
                                                        )->label(false);?>
                                                <?= $form->field($model1, 'description')->textarea()->label('Отзыв');?>
                                                <div class="form-group">
                                                    <?= Html::submitButton(Yii::t('app', 'Опубликовать'), ['class' => 'btn btn-common review']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php ActiveForm::end(); ?>
                                <?php else: ?>
                                    <h3>Невозможно добавить отзыв. Администрация данного объекта выключила функцию добавления отзывов об этом объекте.</h3>
                                <?php endif; ?>
                                <?php Modal::end(); ?>
                            </div>
                            <div class="item-title pull-left">
                                <?php $servicetitle = "";
                                foreach (explode('-', Servis::findOne(['id'=>$object->service])->aliastwo) as $stitle) {
                                    $servicetitle .= $stitle . " ";
                                }
                                $objtitle = $servicetitle . $object->title;?>
                                <h3><?=Html::a($objtitle, ['/'.$object->alias])?></h3>
                            </div>
                        </div>
                        <div class="item-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Адрес</th>
                                        <td><?=$object->address?></td>
                                    </tr>
                                    <tr>
                                        <th>Категория номера: <span class="bron-sp"><?=$catroom?></span></th>
                                        <th>Телефон объекта: <span class="bron-sp"><?=$phone?></span></th>
                                    </tr>
                                    <tr>
                                        <th>Заезд: <span class="bron-sp"><?=$model->from?></span></th>
                                        <th>Выезд: <span class="bron-sp"><?=$model->to?></span></th>
                                    </tr>
                                    <tr>
                                        <th>Количество гостей</th>
                                        <td><span class="bron-sp">взрослых: <?=$model->adult_count?><?=($model->child_count)?', детей: '.$model->child_count.' (до '.$model->childs_ages.' лет)':''?></span></td>
                                    </tr>
                                    <tr>
                                        <th>Общая сумма за <?=$tarb?> суток</th>
                                        <td><?=$model->price?> руб.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
