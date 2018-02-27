<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\LinkPager;
use app\models\Image;
use app\models\Servis;
use app\models\Food;
use app\models\Review;
use app\models\Rate;
use app\models\Properties;
use app\models\Curency;
use app\models\Config;

$url = explode('?', Url::current())[0];
$this->title = $title;
/* @var $this yii\web\View */
?>
<?= $this->registerMetaTag(['name' => 'description','content' => $title]); ?>
<?= $this->registerMetaTag(['name' => 'keywords','content' => $title]); ?>

<div class="objects-div">
    <div class="title">
        <h1 class="text-primary text-center"><?=$title?></h1>
    </div>
    <?php if (count($objects) != 0): ?>
        <div class="sort-block">
            <div class="dropdown">
                <b class="text-primary">Сортировать:</b>
            </div>
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" data-toggle="dropdown">По цене</button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li class="asc <?=(Url::current() == $url.'?sort=price')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=price">От меньшей к большей</a></li>
                    <li class="desc <?=(Url::current() == $url.'?sort=-price')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=-price">От большей к меньшей</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" data-toggle="dropdown">По рейтингу на основе голосов</button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li class="asc <?=(Url::current() == $url.'?sort=-rate')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=-rate">С высоким рейтингом</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" data-toggle="dropdown">По удаленности от центра</button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li class="asc <?=(Url::current() == $url.'?sort=center')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=center">Ближе</a></li>
                    <li class="desc <?=(Url::current() == $url.'?sort=-center')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=-center">Дальше</a></li>
                </ul>
            </div>
            <?php if (Config::findOne(["id"=>1])->objectcat_id == 1): ?>
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-toggle="dropdown">По удаленности от моря</button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li class="asc <?=(Url::current() == $url.'?sort=fromsea')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=fromsea">Ближе</a></li>
                        <li class="desc <?=(Url::current() == $url.'?sort=-fromsea')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=-fromsea">Дальше</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-toggle="dropdown">По высоте над уровнем моря</button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li class="asc <?=(Url::current() == $url.'?sort=highsea')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=highsea">Ниже</a></li>
                        <li class="desc <?=(Url::current() == $url.'?sort=-highsea')? 'active':''?>" role="presentation"><a role="menuitem" rel="canonical" tabindex="-1" href="<?=$url?>?sort=-highsea">Выше</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <?php foreach ($objects as $object): ?>
            <div class="result-item s-list">
                <?php
                if ($objectimage=Image::find()->where(['object_id'=>$object->id])->orderBy('id')->one()) {
                    $image = $objectimage->image;
                } else {
                    $image = "upload/images/default/default.jpg";
                } ?>
                <div class="img-div">
                    <?= Html::a(Html::img(Yii::$app->request->baseUrl."/".$image),['/'.$object->alias])?>
                </div>
                <div class="desc-div">
                    <h3 class="title">
                        <?= Html::a($object->full_title,['/'.$object->alias], ['class'=>"title underline"])?>
                        <p class="pull-right text-right">
                            <?=($object->tarif_id==4)?'<span class="online-b">Онлайн бронирование</span>':''?>
                            <?=($object->tarif_id!=0 && Properties::findOne(['object_id'=>$object->id, 'field_id'=>"12"]))
                                ?'<span class="item-price">Цена от '.Properties::findOne(['object_id'=>$object->id, 'field_id'=>"12"])->field_value.' '.Curency::findOne(['id'=>$object->curency_id])->mini_title.' чел./сутки</span>'
                                :''?>
                            <?=($object->tarif_id!=0 && $object->food_id && $object->food_id != 1)?"<span class='item-food'>(".Food::findOne(['id'=>$object->food_id])->title.")</span>" : ""?>
                        </p>
                    </h3>
                    <address><?=Properties::findOne(['object_id'=>$object->id, 'field_id'=>"5"])->field_value?></address>
                    <div class="properties">
                        <?php if ($object->tarif_id == 4): ?>
                            <?php $count = count(Review::find()->where(['object_id'=>$object->id])->andWhere(['status'=>1])->all()); $rate = Rate::findOne(['object_id'=>$object->id]); ?>
                            <p class="item-rete">рейтинг <?=($rate)?$rate->rate:'0'?> (<?=($object->allow_review==1)?$count:'0'?> голосов)</p>
                        <?php endif; ?>
                        <p>Функционирует: <?=(Properties::findOne(['object_id'=>$object->id, 'field_id'=>"32"]))?Properties::findOne(['object_id'=>$object->id, 'field_id'=>"32"])->field_value:'круглогодично'?></p>
                        <?php if (Properties::findOne(['object_id'=>$object->id, 'field_id'=>"2"]) && Properties::findOne(['object_id'=>$object->id, 'field_id'=>"2"])->field_value == "0"): ?>
                            <?=(Properties::findOne(['object_id'=>$object->id, 'field_id'=>"2"]))?'<p>Расстояние до центра: В центре</p>':''?>
                        <?php else: ?>
                            <?=(Properties::findOne(['object_id'=>$object->id, 'field_id'=>"2"]))?'<p>Расстояние до центра: '.Properties::findOne(['object_id'=>$object->id, 'field_id'=>"2"])->field_value.' метров</p>':''?>
                        <?php endif; ?>
                        <?=(Properties::findOne(['object_id'=>$object->id, 'field_id'=>"1"]))?'<p>Расстояние до моря: '.Properties::findOne(['object_id'=>$object->id, 'field_id'=>"1"])->field_value.' метров</p>':''?>
                        <?=(Properties::findOne(['object_id'=>$object->id, 'field_id'=>"34"]))?'<p>Высота над уровнем моря: '.Properties::findOne(['object_id'=>$object->id, 'field_id'=>"34"])->field_value.' метров</p>':''?>
                    </div>
                    <p><?=substr($object->general,0,1000)?>...  <?= Html::a("Подробнее",['/'.$object->alias], ['class'=>"title underline"])?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <?= LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    <?php endif; ?>
    <div class="main-content text-center">
        <?=$description?>
    </div>
</div>
