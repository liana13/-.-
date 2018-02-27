<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\Image;
use app\models\Person;
use app\models\Review;
use app\models\User;
use app\models\Properties;
use mirocow\yandexmaps\Canvas as YandexCanvas;
use mirocow\yandexmaps\Map as YandexMap;
use mirocow\yandexmaps\objects\Placemark as Placemark;
use yii\bootstrap\Modal;
use app\models\Country;
use app\models\Locality;
use app\models\Region;
use app\models\Field;
use app\models\Servis;
use app\models\Bookmark;
use app\models\AdminForm;

$servicetitle = "";
foreach (explode('-', Servis::findOne(['id'=>$model->service])->aliastwo) as $stitle) {
    $servicetitle .= $stitle . " ";
}
$title = $servicetitle.$model->title;
$reviews = Review::find()->where(['object_id'=>$model->id, 'status'=>1])->all();
if (Person::findOne(['id'=>$model->user_id])) {
    $phone=Person::findOne(['id'=>$model->user_id])->phone;
}
$image=Image::find()->where(['object_id'=>$model->id])->orderBy('id')->one();
$adminform = new AdminForm();
?>
<div class="object-view">
    <div class="row">
        <div class="col-lg-7">
            <div id="slider" class="flexslider single">
                <ul class="slides">
                    <?php if ($image): ?>
                        <li>
                            <?= Html::a(Html::img(Html::encode(Yii::$app->request->baseUrl.'/'. $image->image), ['title'=>(!empty($image->value))?$image->value:'', 'alt' => Html::encode($model->title), 'class'=>"clasadimage"]),
                                            Html::encode(Yii::$app->request->baseUrl.'/'. $image->image), ['data-fancybox'=>"gallery"])?>
                        </li>
                    <?php else: ?>
                        <li>
                            <img src="<?=Yii::$app->request->baseUrl?>/upload/images/default/default.jpg" alt="<?=Html::encode($model->title)?>"/>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-5 object-info">
            <div class="text-center">
                <?php if (!Yii::$app->user->isGuest && User::findOne(['id'=>Yii::$app->user->getId()])->type==2): ?>
                    <?php if (Bookmark::findOne(['user_id'=>Yii::$app->user->getId(), 'object_id'=>$model->id])): ?>
                        <?=Html::a('<i class="fa fa-heart" aria-hidden="true"></i>', ['/object/bookmark?id='.$model->id], ['title'=>'Удалить из закладок', 'class'=>'pull-right'])?>
                    <?php else: ?>
                        <?=Html::a('<i class="fa fa-heart-o" aria-hidden="true"></i>', ['/object/bookmark?id='.$model->id], ['title'=>'Добавить в закладки', 'class'=>'pull-right'])?>
                    <?php endif; ?>
                <?php endif; ?>
                <h1 class="title"><?= $title?></h1>
            </div>
            <?php if (Properties::findOne(['field_id'=>5, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>5])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>5, 'object_id'=>$model->id])->field_value?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>7, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>7])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>7, 'object_id'=>$model->id])->field_value?>
                      <p><small class="text-danger">При звонке, пожалуйста, ссылайтесь на портал <?=Yii::$app->name?></small></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>32, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>32])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>32, 'object_id'=>$model->id])->field_value?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>1, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-ship" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>1])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>1, 'object_id'=>$model->id])->field_value." метров"?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>2, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-building-o" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>2])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                        <?php if (Properties::findOne(['field_id'=>2, 'object_id'=>$model->id])->field_value != 0): ?>
                            <?=Properties::findOne(['field_id'=>2, 'object_id'=>$model->id])->field_value." метров"?>
                        <?php else: ?>
                            <?="В центре"?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Properties::findOne(['field_id'=>34, 'object_id'=>$model->id])): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <i class="fa fa-level-up" aria-hidden="true"></i>
                        <span><?=Field::findOne(['id'=>34])->title?>: </span>
                    </div>
                    <div class="col-sm-7">
                      <?=Properties::findOne(['field_id'=>34, 'object_id'=>$model->id])->field_value." метров"?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="texttop">
        <?php if ($model->general): ?>
            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    <span>Основное: </span>
                </div>
                <div class="col-sm-9">
                    <p><?=$model->general?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <p><small>Информация размещена объектом. Администрация портала не несёт ответственность за достоверность размещённых данных.</small></p>
    <p class="text-primary toggle-adminform">Сообщить об ошибке администрации портала</p>
    <div class="contact-admin" style="display:none";>
        <div class="row">
            <div class="col-sm-5">
                <?php $form = ActiveForm::begin(['action'=>['/site/contactadmin']]); ?>
                    <?= $form->field($adminform, 'objectid')->hiddenInput(['value' => $model->id])->label(false); ?>
                    <?= $form->field($adminform, 'object')->hiddenInput(['value' => $title])->label(false); ?>
                    <?= $form->field($adminform, 'email') ?>
                    <?= $form->field($adminform, 'body')->textarea(['rows' => 6]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-modal-open btn-common', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
