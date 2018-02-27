<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Image;
use app\models\Servis;
use app\models\Country;
use app\models\Region;
use app\models\Locality;
use app\models\Food;
use app\models\Review;
use app\models\Rate;
use app\models\Properties;
use app\models\Curency;

if ($modelimage=Image::find()->where(['object_id'=>$model->id])->orderBy('id')->one()) {
    $image = $modelimage->image;
} else {
    $image = "upload/images/default/default.jpg";
} ?>
<div class="img-div">
    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/".$image),['/'.$model->alias])?>
</div>
<div class="desc-div">
    <h3 class="title">
        <?= Html::a($model->full_title,['/'.$model->alias], ['class'=>"title underline"])?>
        <p class="pull-right text-right">
            <?=($model->tarif_id==4)?'<span class="online-b">Онлайн бронирование</span>':''?>
            <?=($model->tarif_id!=0 && Properties::findOne(['object_id'=>$model->id, 'field_id'=>"12"]))
                ?'<span class="item-price">Цена от '.Properties::findOne(['object_id'=>$model->id, 'field_id'=>"12"])->field_value.' '.Curency::findOne(['id'=>$model->curency_id])->mini_title.' чел./сутки</span>'
                :''?>
            <?=($model->tarif_id!=0 && $model->food_id && $model->food_id != 1)?"<span class='item-food'>(".Food::findOne(['id'=>$model->food_id])->title.")</span>" : ""?>
        </p>
    </h3>
    <address><?=Properties::findOne(['object_id'=>$model->id, 'field_id'=>"5"])->field_value?></address>
    <div class="properties">
        <?php if ($model->tarif_id == 4): ?>
            <?php $count = count(Review::find()->where(['object_id'=>$model->id])->all()); $rate = Rate::findOne(['object_id'=>$model->id]); ?>
            <p class="item-rete">рейтинг <?=($rate)?$rate->rate:'0'?> (<?=($object->allow_review==1)?$count:'0'?> голосов)</p>
        <?php endif; ?>
        <p>Функционирует: <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>"32"]))?Properties::findOne(['object_id'=>$model->id, 'field_id'=>"32"])->field_value:'круглогодично'?></p>
        <?php if (Properties::findOne(['object_id'=>$model->id, 'field_id'=>"2"])->field_value == "0"): ?>
            <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>"2"]))?'<p>Расстояние до центра: В центре</p>':''?>
        <?php else: ?>
            <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>"2"]))?'<p>Расстояние до центра: '.Properties::findOne(['object_id'=>$model->id, 'field_id'=>"2"])->field_value.' метров</p>':''?>
        <?php endif; ?>
        <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>"1"]))?'<p>Расстояние до моря: '.Properties::findOne(['object_id'=>$model->id, 'field_id'=>"1"])->field_value.' метров</p>':''?>
        <?=(Properties::findOne(['object_id'=>$model->id, 'field_id'=>"34"]))?'<p>Высота над уровнем моря: '.Properties::findOne(['object_id'=>$model->id, 'field_id'=>"34"])->field_value.' метров</p>':''?>
    </div>
    <p><?=substr($model->general,0,1000)?>...  <?= Html::a("Подробнее",['/'.$model->alias], ['class'=>"title underline"])?></p>
</div>
