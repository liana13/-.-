<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\db\Query;
use app\models\Catroom;
use app\models\Childage;
use app\models\Food;
use app\models\Room;
use app\models\Price;
use app\models\Calendar;
use app\models\User;
use app\models\Discount;
use app\models\Weekdays;

$user = User::findOne(['id'=>Yii::$app->user->getId()]);
$childs = Childage::find()->where(['catroom_id'=>$model->id])->all();
$ch="";
for ($i=0; $i < count($childs); $i++) {
    if ($i!=count($childs)-1) {
        $ch .= $childs[$i]->child_age.",";
    } else {
        $ch .= $childs[$i]->child_age;
    }
}
$adult = Yii::$app->request->get('filter')['adult'];
$child = Yii::$app->request->get('filter')['child'];
$ages = []; $chages = [];
for ($i=1; $i <= $child ; $i++) {
    $a = 'age_'.$i;
    if (!empty(Yii::$app->request->get('filter')[$a])) {
        $ages []= Yii::$app->request->get('filter')[$a];
    } else {
        $ages []= '0';
    }
}
foreach ($childs as $childage) {
    $chages []= $childage->child_age;
}
while (count($ages)!=0 && count($chages)!=0) {
    if (min($ages) <= min($chages)) {
        unset($ages[array_search(min($ages),$ages)]);
        unset($chages[array_search(min($chages),$chages)]);
    }else {
        unset($chages[array_search(min($chages),$chages)]);
    }
}
$checkweek = date('w', strtotime(date('Y-m-d')));
if (Weekdays::findOne(['object_id'=>$model->object_id])) {
    $weekdays = Weekdays::findOne(['object_id'=>$model->object_id])->week_days;
} else {
    $weekdays = "";
}
$prices = Price::find()->where(['catroom_id'=>$model->id])->andWhere(['check_date'=>date('Y-m-d')])->one();
if (preg_match('/'.$checkweek.'/',$weekdays)) {
    if ($prices && $prices->weekend) {
        $price = $prices->weekend;
    } else {
        $price = $model->weekend;
    }
} else {
    if ($prices && $prices->work_day) {
        $price = $prices->work_day;
    } else {
        $price = $model->work_day;
    }
}
if (!empty($adult) && empty($child)) {
    if ($adult > $model->adult_count && $adult <= $model->adult_count+$model->add_count && $model->add_count!=0) {
        $add = $adult - $model->adult_count;
        if (preg_match('/'.$checkweek.'/',$weekdays)) {
            if ($prices && $prices->weekend_add) {
                $price += $add*$prices->weekend_add;
            } else {
                $price += $add*$model->weekend_add;
            }
        } else {
            if ($prices && $prices->work_add) {
                $price += $add*$prices->work_add;
            } else {
                $price += $add*$model->work_add;
            }
        }
    }
} elseif (!empty($adult) && !empty($child)) {
    if ($adult > $model->adult_count && $adult <= $model->adult_count+$model->add_count && $model->add_count!=0) {
        $add = $adult - $model->adult_count;
        if (preg_match('/'.$checkweek.'/',$weekdays)) {
            if ($prices && $prices->weekend_add) {
                $price += $add*$prices->weekend_add;
            } else {
                $price += $add*$model->weekend_add;
            }
        } else {
            if ($prices && $prices->work_add) {
                $price += $add*$prices->work_add;
            } else {
                $price += $add*$model->work_add;
            }
        }
        if (count($ages)>0) {
            foreach ($ages as $age) {
                if ($discount = Discount::find()->where(['catroom_id'=>$model->id])->andWhere(['<=', 'fromage', $age])->andWhere(['>=', 'age', $age])->one()) {
                    if (preg_match('/'.$checkweek.'/',$weekdays)) {
                        if ($prices && $prices->weekend_add) {
                            $price += $prices->weekend_add-$prices->weekend_add*$discount->percent/100;
                        } else {
                            $price += $model->weekend_add-$model->weekend_add*$discount->percent/100;
                        }
                    } else {
                        if ($prices && $prices->work_add) {
                            $price += $prices->work_add-$prices->work_add*$discount->percent/100;
                        } else {
                            $price += $model->work_add-$model->work_add*$discount->percent/100;
                        }
                    }
                } else {
                    if (preg_match('/'.$checkweek.'/',$weekdays)) {
                        if ($prices && $prices->weekend_add) {
                            $price += $prices->weekend_add;
                        } else {
                            $price += $model->weekend_add;
                        }
                    } else {
                        if ($prices && $prices->work_add) {
                            $price += $prices->work_add;
                        } else {
                            $price += $model->work_add;
                        }
                    }
                }
            }
        }
    }
}
?>
<div class="row">
    <div class="col-sm-4">
         <div class="row">
             <div class="col-sm-8">
                 <div id="slider-cat<?=$model->id?>" class="flexslider <?=(count($model->photo)==1) ? 'single' : ''?>">
                     <ul class="slides">
                         <?php if (Catroom::find()->where(['id'=>$model->id])->one()): ?>
                             <?php if (count($model->photo) != 0): ?>
                                 <?php foreach (explode(",", $model->photo) as $expphoto): ?>
                                     <li> <?= Html::a(Html::img(Yii::$app->request->baseUrl."/upload/catroom/".$expphoto),["/upload/catroom/".$expphoto],['data-fancybox'=>"gallery", 'rel'=>"gal-".$model->id])?></li>
                                 <?php endforeach; ?>
                             <?php else: ?>
                                 <li><img src="<?=Yii::$app->request->baseUrl?>/upload/catroom/default/default.jpg" alt="<?=Html::encode($model->title)?>"/></li>
                             <?php endif; ?>
                         <?php endif; ?>
                     </ul>
                     <div class="count_price">
                         <p><?=count(explode(",", $model->photo))?> фото</p>
                     </div>
                 </div>
             </div>
             <div class="col-sm-4 slidesvertical" id="slidesvertical">
                 <div id="carousel-cat<?=$model->id?>" class="flexslider">
                     <ul class="slides">
                         <?php if (count($model->photo) != 0): ?>
                             <?php foreach (explode(",", $model->photo) as $expphoto): ?>
                                 <li> <?= Html::a(Html::img(Yii::$app->request->baseUrl."/upload/catroom/".$expphoto),["/upload/catroom/".$expphoto],['data-fancybox'=>"gallery", 'rel'=>"gal-".$model->id])?></li>
                             <?php endforeach; ?>
                         <?php else: ?>
                             <li><img src="<?=Yii::$app->request->baseUrl?>/upload/catroom/default/default.jpg" alt="<?=Html::encode($model->title)?>"/></li>
                         <?php endif; ?>
                     </ul>
                 </div>
             </div>
         </div>
    </div>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-success"><?=$model->title?><span>Кол-во комнат в номере <?=$model->room_count?>, (Максимальная вместимость: Взр. <?=$model->adult_count?><?=($model->child_count)?', Детей бесплатно (без доп. места): '.count($childs)
                .' (до '.$ch.' лет)':''?><?=($model->add_count)?', Доп. мест: '.$model->add_count:''?>, Питание: <?=Food::findOne(['id'=>$model->food_id])->title?>.)</span></h3>
            </div>
            <div class="col-sm-7">
                <p> Описание:</p>
                <p class="description" id="description<?=$model->id?>"><span class="span-desc"><?=$model->description?></span></p>
                <b id="podrobnee<?=$model->id?>" class="more text-primary" data-text="Свернуть" onclick="toggleDesc(<?=$model->id?>)">Подробнее ...</b>
            </div>
            <div class="col-sm-2">
                <span>Кол-во номеров:</span>
                <p><?=$model->count_rooms?></p>
            </div>
            <div class="col-sm-3">
                <span>Цена за номер в сутки сегодня</span>
                <p><?=$price?> руб.</p>
                <?php if (Yii::$app->user->isGuest): ?>
                    <?php Modal::begin([
                        'header' => '<h2>Онлайн бронирование</h2>',
                        'size'=>'modal-sm',
                        'toggleButton' => ['label' => 'Бронировать', 'class' => 'btn btn-modal-open bronirovat btn-common '],
                    ]);?>
                    <?="<p>". Html::a('Авторизуйтесь, как пользователь портала',[''], ['class'=>'login-btn', "data-dismiss"=>"modal"]) . ", чтобы бронировать.</p>"?>
                    <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
                    <?php Modal::end(); ?>
                <?php elseif($user->type!=2): ?>
                    <?php Modal::begin([
                        'header' => '<h2>Онлайн бронирование</h2>',
                        'size'=>'modal-sm',
                        'toggleButton' => ['label' => 'Бронировать', 'class' => 'btn btn-modal-open bronirovat btn-common '],
                    ]);?>
                    <h4 class="text-primary text-center">Авторизуйтесь, как пользователь портала, чтобы бронировать.</h4>
                    <?= Html::button('Ok',['class'=>'btn-close btn btn-login btn-common', "data-dismiss"=>"modal", "aria-hidden"=>true])?>
                    <?php Modal::end(); ?>
                <?php else: ?>
                    <?php Modal::begin([
                        'header' => '<h2>Онлайн бронирование</h2>',
                        'size'=>'modal-sm',
                        'toggleButton' => ['label' => 'Бронировать', 'class' => 'btn btn-modal-open bronirovat btn-common '],
                    ]);?>
                    <h4 class="text-primary text-center">Пожалуйста, выберите сначала даты заезда и отъезда и количество гостей</h4>
                    <?php Modal::end(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $js = "
var mql = window.matchMedia('all and (max-width: 768px)');
if (mql.matches) {
    $('#carousel-cat".$model->id."').flexslider({
    animation: 'slide',
    controlNav: false,
    animationLoop: true,
    slideshow: false,
    itemWidth: 68,
    asNavFor: '#slider-cat".$model->id."'
     });
} else {
    $('#carousel-cat".$model->id."').flexslider({
    animation: 'slide',
    controlNav: false,
    animationLoop: true,
    direction: 'vertical',
    slideshow: false,
    itemWidth: 68,
    asNavFor: '#slider-cat".$model->id."'
     });
}

 $('#slider-cat".$model->id."').flexslider({
     animation: 'slide',
  controlNav: false,
  border: true,
  animationLoop: true,
  slideshow: false,
  sync: '#carousel-cat".$model->id."'
 });";
$this->registerJs($js);?>
