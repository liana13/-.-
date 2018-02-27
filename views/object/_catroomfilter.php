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
use app\models\Addprice;
use app\models\Calendar;
use app\models\Discount;
use app\models\User;
use app\models\Weekdays;
use app\models\Freeroom;

$user = User::findOne(['id'=>Yii::$app->user->getId()]);
$from = Yii::$app->request->get('filter')['from'];
$to = Yii::$app->request->get('filter')['to'];
$datediff = strtotime($to) - strtotime($from);
$tarb = floor($datediff / (60 * 60 * 24));

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
$max = [];
foreach ($childs as $childage) {
    $chages []= $childage->child_age;
}
if (count(Freeroom::find()->where(['catroom_id'=>$model->id])->andWhere(['<', 'check_date', $to])->andWhere(['>=', 'check_date', $from])->all()) !=0) {
    $modelrooms = Freeroom::find()->where(['catroom_id'=>$model->id])->andWhere(['<', 'check_date', $to])->andWhere(['>=', 'check_date', $from])->orderBy('room_count asc')->all()[0]->room_count;
} else {
    $modelrooms = $model->count_rooms;
}

while (count($ages)!=0 && count($chages)!=0) {
    if (max($ages) <= max($chages)) {
        unset($ages[array_search(max($ages),$ages)]);
        unset($chages[array_search(max($chages),$chages)]);
    }else {
        $max[] = max($ages);
        unset($ages[array_search(max($ages),$ages)]);
    }
}
$ages = array_merge($ages, $max);
$date = $from;
$price = 0;
$child_adult = $model->adult_count - $adult;
$a = 0;
while ($a < $child_adult) {
    if (count($ages)!=0) {
        unset($ages[array_search(max($ages),$ages)]);
        $a++;
    } else {
        $a=$child_adult;
    }
}
while ($date < $to) {
    $prices = Price::find()->where(['catroom_id'=>$model->id])->andWhere(['check_date'=>$date])->one();
    $addprices = Addprice::find()->where(['catroom_id'=>$model->id])->andWhere(['check_date'=>$date])->one();
    $checkweek = date('w', strtotime($date));
    if (Weekdays::findOne(['object_id'=>$model->object_id])) {
        $weekdays = Weekdays::findOne(['object_id'=>$model->object_id])->week_days;
    } else {
        $weekdays = "";
    }
    if (preg_match('/'.$checkweek.'/',$weekdays)) {
        if ($prices && $prices->weekend) {
            $price += $prices->weekend;
        } else {
            $price += $model->weekend;
        }
    } else {
        if ($prices && $prices->work_day) {
            $price += $prices->work_day;
        } else {
            $price += $model->work_day;
        }
    }
    if (!empty($adult) && empty($child)) {
        if ($adult > $model->adult_count && $adult <= $model->adult_count+$model->add_count && $model->add_count!=0) {
            $add = $adult - $model->adult_count;
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                if ($addprices && $addprices->weekend) {
                    $price += $add*$addprices->weekend;
                } else {
                    $price += $add*$model->weekend_add;
                }
            } else {
                if ($addprices && $addprices->work_day) {
                    $price += $add*$addprices->work_day;
                } else {
                    $price += $add*$model->work_add;
                }
            }
        }
    } elseif (!empty($adult) && !empty($child)) {
        if ($adult >= $model->adult_count && $adult <= $model->adult_count+$model->add_count && $model->add_count!=0) {
            $add = $adult - $model->adult_count;
            if (preg_match('/'.$checkweek.'/',$weekdays)) {
                if ($addprices && $addprices->weekend) {
                    $price += $add*$addprices->weekend;
                } else {
                    $price += $add*$model->weekend_add;
                }
            } else {
                if ($addprices && $addprices->work_day) {
                    $price += $add*$addprices->work_day;
                } else {
                    $price += $add*$model->work_add;
                }
            }
            if (count($ages)>0) {
                foreach ($ages as $age) {
                    if ($discount = Discount::find()->where(['catroom_id'=>$model->id])->andWhere(['<=', 'fromage', (int)$age])->andWhere(['>=', 'age', (int)$age])->one()) {
                        if (preg_match('/'.$checkweek.'/',$weekdays)) {
                            if ($addprices && $addprices->weekend) {
                                $price += $addprices->weekend-$addprices->weekend*$discount->percent/100;
                            } else {
                                $price += $model->weekend_add-$model->weekend_add*$discount->percent/100;
                            }
                        } else {
                            if ($addprices && $addprices->work_day) {
                                $price += $addprices->work_day-$addprices->work_day*$discount->percent/100;
                            } else {
                                $price += $model->work_add-$model->work_add*$discount->percent/100;
                            }
                        }
                    } else {
                        if (preg_match('/'.$checkweek.'/',$weekdays)) {
                            if ($addprices && $addprices->weekend) {
                                $price += $addprices->weekend;
                            } else {
                                $price += $model->weekend_add;
                            }
                        } else {
                            if ($addprices && $addprices->work_day) {
                                $price += $prices->work_day;
                            } else {
                                $price += $model->work_add;
                            }
                        }
                    }
                }
            }
        } elseif ($adult < $model->adult_count && count($ages)>0) {
            if (count($ages)!=0) {
                foreach ($ages as $age) {
                    if ($discount = Discount::find()->where(['catroom_id'=>$model->id])->andWhere(['<=', 'fromage', (int)$age])->andWhere(['>=', 'age', (int)$age])->one()) {
                        if (preg_match('/'.$checkweek.'/',$weekdays)) {
                            if ($addprices && $addprices->weekend) {
                                $price += $addprices->weekend-$addprices->weekend*$discount->percent/100;
                            } else {
                                $price += $model->weekend_add-$model->weekend_add*$discount->percent/100;
                            }
                        } else {
                            if ($addprices && $addprices->work_day) {
                                $price += $addprices->work_day-$addprices->work_day*$discount->percent/100;
                            } else {
                                $price += $model->work_add-$model->work_add*$discount->percent/100;
                            }
                        }
                    } else {
                        if (preg_match('/'.$checkweek.'/',$weekdays)) {
                            if ($addprices && $addprices->weekend) {
                                $price += $addprices->weekend;
                            } else {
                                $price += $model->weekend_add;
                            }
                        } else {
                            if ($addprices && $addprices->work_day) {
                                $price += $addprices->work_day;
                            } else {
                                $price += $model->work_add;
                            }
                        }
                    }
                }
            }
        }
    }
    $date = date('Y-m-d',strtotime($date . "+1 days"));
}
?>
<?php if ($modelrooms != 0): ?>
    <div class="list-view list-obj-cat" data-key=<?=$model->id?>>
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
                        <span>Kол-во свободных номеров:</span>
                        <p><?=$modelrooms?></p>
                    </div>
                    <div class="col-sm-3">
                        <span>Общая сумма за <?=$tarb?> суток</span>
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
                                'toggleButton' => ['label' => 'Бронировать', 'class' => 'btn btn-modal-open bronirovat btn-common '],
                            ]);?>
                            <?= $this->render('_booking', ['model'=>$model->object_id, 'catroom'=>$model, 'money'=>$price]) ?>
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
    </div>
<?php endif; ?>
